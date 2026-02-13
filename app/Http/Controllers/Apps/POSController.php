<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

/**
 * POSController
 * 
 * Handles Point of Sale operations.
 * Access: Admin & Kasir only
 * 
 * Features:
 * - Display POS interface
 * - Process checkout
 * - Save draft transactions
 * - View transaction history
 * - Print receipts
 */
class POSController extends Controller
{
    /**
     * Display POS interface
     */
    public function index()
    {
        // Get available products (active and in stock)
        $products = Product::where('status', 'active')
            ->where('stock', '>', 0)
            ->with('category')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'category' => $product->category?->name,
                    'image' => $product->image,
                ];
            });

        // Get today's statistics for kasir
        $todayStats = [
            'transactions' => Transaction::whereDate('created_at', today())
                ->where('cashier_id', auth()->id())
                ->count(),
            'revenue' => Transaction::whereDate('created_at', today())
                ->where('cashier_id', auth()->id())
                ->sum('total'),
            'items_sold' => TransactionItem::whereHas('transaction', function($q) {
                $q->whereDate('created_at', today())
                  ->where('cashier_id', auth()->id());
            })->sum('quantity'),
        ];

        // Get recent customers for quick selection
        $recentCustomers = Customer::where('status', 'active')
            ->latest()
            ->take(10)
            ->get(['id', 'name', 'phone']);

        return Inertia::render('POS/Index', [
            'products' => $products,
            'todayStats' => $todayStats,
            'recentCustomers' => $recentCustomers,
        ]);
    }

    /**
     * Process checkout and create transaction
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,transfer,e-wallet',
            'payment_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Create or get customer
            $customerId = $validated['customer_id'];
            if (!$customerId && $validated['customer_name']) {
                $customer = Customer::create([
                    'name' => $validated['customer_name'],
                    'phone' => $validated['customer_phone'] ?? null,
                    'status' => 'active',
                ]);
                $customerId = $customer->id;
            }

            // Generate transaction number
            $transactionNumber = 'TRX-' . date('Ymd') . '-' . str_pad(
                Transaction::whereDate('created_at', today())->count() + 1,
                4,
                '0',
                STR_PAD_LEFT
            );

            // Create transaction
            $transaction = Transaction::create([
                'transaction_number' => $transactionNumber,
                'customer_id' => $customerId,
                'cashier_id' => auth()->id(),
                'subtotal' => $validated['subtotal'],
                'tax' => $validated['tax'],
                'discount' => $validated['discount'] ?? 0,
                'total' => $validated['total'],
                'payment_method' => $validated['payment_method'],
                'payment_amount' => $validated['payment_amount'],
                'change_amount' => $validated['payment_amount'] - $validated['total'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'completed',
            ]);

            // Create transaction items and update stock
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Check stock availability
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                // Create transaction item
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Update product stock
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return redirect()->route('pos.index')
                ->with('success', [
                    'message' => 'Transaction completed successfully!',
                    'transaction_id' => $transaction->id,
                    'transaction_number' => $transaction->transaction_number,
                    'total' => $transaction->total,
                    'change' => $transaction->change_amount,
                ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors([
                'error' => 'Transaction failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Save transaction as draft
     */
    public function saveDraft(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'customer_id' => 'nullable|exists:customers,id',
            'notes' => 'nullable|string',
        ]);

        $draft = Transaction::create([
            'transaction_number' => 'DRAFT-' . time(),
            'customer_id' => $validated['customer_id'] ?? null,
            'cashier_id' => auth()->id(),
            'subtotal' => 0,
            'tax' => 0,
            'total' => 0,
            'status' => 'draft',
            'notes' => $validated['notes'] ?? null,
            'draft_data' => json_encode($validated['items']),
        ]);

        return back()->with('success', 'Draft saved successfully.');
    }

    /**
     * View transaction history
     */
    public function transactions(Request $request)
    {
        $query = Transaction::with(['customer', 'cashier', 'items'])
            ->where('status', 'completed');

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by cashier (for admin view)
        if ($request->has('cashier_id') && auth()->user()->hasRole('admin')) {
            $query->where('cashier_id', $request->cashier_id);
        } else {
            // Kasir only sees their own transactions
            if (auth()->user()->hasRole('kasir')) {
                $query->where('cashier_id', auth()->id());
            }
        }

        // Filter by payment method
        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Search by transaction number
        if ($request->has('search')) {
            $query->where('transaction_number', 'like', '%' . $request->search . '%');
        }

        $transactions = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total_transactions' => $query->count(),
            'total_revenue' => $query->sum('total'),
            'avg_transaction' => $query->avg('total'),
        ];

        return Inertia::render('POS/Transactions', [
            'transactions' => $transactions,
            'stats' => $stats,
            'filters' => $request->only(['date_from', 'date_to', 'cashier_id', 'payment_method', 'search']),
        ]);
    }

    /**
     * Show specific transaction details
     */
    public function showTransaction(Transaction $transaction)
    {
        // Check authorization
        if (auth()->user()->hasRole('kasir') && $transaction->cashier_id !== auth()->id()) {
            abort(403, 'You can only view your own transactions.');
        }

        $transaction->load(['customer', 'cashier', 'items.product']);

        return Inertia::render('POS/TransactionDetail', [
            'transaction' => $transaction,
        ]);
    }
}