<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('stock_status')) {
            match ($request->stock_status) {
                'in_stock'     => $query->where('stock', '>', 10),
                'low_stock'    => $query->whereBetween('stock', [1, 10]),
                'out_of_stock' => $query->where('stock', 0),
                default        => null,
            };
        }

        $products   = $query->orderBy($request->get('sort', 'created_at'), $request->get('order', 'desc'))
                            ->paginate(15)
                            ->withQueryString();
        $categories = Category::all();

        return Inertia::render('Apps/Products', [
            'products'   => $products,
            'categories' => $categories,
            'filters'    => $request->only(['search', 'category', 'status', 'stock_status']),
            'stats'      => [
                'total'        => Product::count(),
                'active'       => Product::where('status', 'active')->count(),
                'low_stock'    => Product::whereBetween('stock', [1, 10])->count(),
                'out_of_stock' => Product::where('stock', 0)->count(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'sku'         => 'required|string|max:100|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'cost'        => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'min_stock'   => 'nullable|integer|min:0',
            'status'      => 'required|in:active,inactive,draft',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'sku'         => 'required|string|max:100|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'cost'        => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'min_stock'   => 'nullable|integer|min:0',
            'status'      => 'required|in:active,inactive,draft',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return back()->with('success', 'Produk berhasil diupdate.');
    }

    public function destroy(Product $product)
    {
        /** @var User $user */
        $user = Auth::user();
        if (! $user || ! $user->isAdmin()) {
            abort(403, 'Hanya admin yang bisa menghapus produk.');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }
}
