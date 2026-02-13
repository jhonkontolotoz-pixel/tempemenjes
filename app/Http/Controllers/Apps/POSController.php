<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class POSController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 'active')
            ->where('stock', '>', 0)
            ->with('category')
            ->get()
            ->map(fn($p) => [
                'id'       => $p->id,
                'name'     => $p->name,
                'sku'      => $p->sku,
                'price'    => $p->price,
                'stock'    => $p->stock,
                'category' => $p->category?->name,
                'image'    => $p->image,
            ]);

        $todayStats = [
            'transactions' => Transaction::whereDate('created_at', today())
                ->where('cashier_id', auth()->id())
                ->where('status', 'completed')
                ->count(),
            'revenue' => Transaction::whereDate('created_at', today())
                ->where('cashier_id', auth()->id())
                ->where('status', 'completed')
                ->sum('total'),
        ];

        $recentCustomers = Customer::where('status', 'active')
            ->latest()
            ->take(10)
            ->get(['id', 'name', 'phone']);

        return Inertia::render('Apps/Pos', [
            'products'        => $products,
            'todayStats'      => $todayStats,
            'recentCustomers' => $recentCustomers,
        ]);
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'customer_id'        => 'nullable|exists:customers,id',
            'customer_name'      => 'nullable|string|max:255',
            'customer_phone'     => 'nullable|string|max:20',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.price'      => 'required|numeric|min:0',
            'subtotal'           => 'required|numeric|min:0',
            'tax'                => 'required|numeric|min:0',
            'discount'           => 'nullable|numeric|min:0',
            'total'              => 'required|numeric|min:0',
            'payment_method'     => 'required|in:cash,card,transfer,e-wallet',
            'payment_amount'     => 'required|numeric|min:0',
            'notes'              => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $customerId = $validated['customer_id'] ?? null;
            if (! $customerId && ! empty($validated['customer_name'])) {
                $customer   = Customer::create([
                    'name'   => $validated['customer_name'],
                    'phone'  => $validated['customer_phone'] ?? null,
                    'status' => 'active',
                ]);
                $customerId = $customer->id;
            }

            $transactionNumber = 'TRX-' . date('Ymd') . '-' . str_pad(
                Transaction::whereDate('created_at', today())->count() + 1,
                4, '0', STR_PAD_LEFT
            );

            $transaction = Transaction::create([
                'transaction_number' => $transactionNumber,
                'customer_id'        => $customerId,
                'cashier_id'         => auth()->id(),
                'subtotal'           => $validated['subtotal'],
                'tax'                => $validated['tax'],
                'discount'           => $validated['discount'] ?? 0,
                'total'              => $validated['total'],
                'payment_method'     => $validated['payment_method'],
                'payment_amount'     => $validated['payment_amount'],
                'change_amount'      => $validated['payment_amount'] - $validated['total'],
                'notes'              => $validated['notes'] ?? null,
                'status'             => 'completed',
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok tidak cukup untuk {$product->name}");
                }

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $product->id,
                    'product_name'   => $product->name,
                    'product_sku'    => $product->sku,
                    'quantity'       => $item['quantity'],
                    'price'          => $item['price'],
                    'subtotal'       => $item['price'] * $item['quantity'],
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return back()->with('success', 'Transaksi berhasil! No: ' . $transaction->transaction_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Transaksi gagal: ' . $e->getMessage()]);
        }
    }
}
