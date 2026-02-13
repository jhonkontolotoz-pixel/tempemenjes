<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

/**
 * ProductController
 * 
 * Handles product management operations.
 * Access: Admin & Manager (Delete only for Admin)
 * 
 * Features:
 * - List all products with filters
 * - Create new product
 * - View product details
 * - Update product information
 * - Delete product (Admin only)
 * - Stock management
 */
class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category !== '') {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by stock status
        if ($request->has('stock_status')) {
            switch ($request->stock_status) {
                case 'in_stock':
                    $query->where('stock', '>', 10);
                    break;
                case 'low_stock':
                    $query->whereBetween('stock', [1, 10]);
                    break;
                case 'out_of_stock':
                    $query->where('stock', 0);
                    break;
            }
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortField, $sortOrder);

        // Pagination
        $products = $query->paginate(15)->withQueryString();

        // Get categories for filter dropdown
        $categories = Category::all();

        return Inertia::render('Products/Index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category', 'status', 'stock_status']),
            'stats' => [
                'total' => Product::count(),
                'active' => Product::where('status', 'active')->count(),
                'low_stock' => Product::whereBetween('stock', [1, 10])->count(),
                'out_of_stock' => Product::where('stock', 0)->count(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $categories = Category::all();

        return Inertia::render('Products/Create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive,draft',
            'image' => 'nullable|image|max:2048',
        ]);

        // Generate SKU if not provided
        if (!isset($validated['sku'])) {
            $validated['sku'] = 'PRD-' . strtoupper(Str::random(8));
        }

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product
     */
    public function show(Product $product)
    {
        $product->load('category');

        return Inertia::render('Products/Show', [
            'product' => $product,
            'stats' => [
                'total_sold' => $product->orderItems()->sum('quantity'),
                'revenue' => $product->orderItems()->sum('total'),
                'avg_rating' => $product->reviews()->avg('rating'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product)
    {
        $categories = Category::all();

        return Inertia::render('Products/Edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive,draft',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                \Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product
     * Note: This route is protected by role:admin middleware
     */
    public function destroy(Product $product)
    {
        // Additional check (optional, already protected by middleware)
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Only administrators can delete products.');
        }

        // Delete product image if exists
        if ($product->image) {
            \Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Update product stock
     * Useful for quick stock adjustments
     */
    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
            'reason' => 'nullable|string',
        ]);

        $product->update(['stock' => $validated['stock']]);

        // Log stock change if needed
        // StockLog::create([...]);

        return back()->with('success', 'Stock updated successfully.');
    }
}