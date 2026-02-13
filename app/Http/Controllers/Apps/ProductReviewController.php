<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

/**
 * ProductReviewController
 * 
 * Handles product review operations.
 * Access: 
 * - Create review: Customers who purchased the product
 * - Manage reviews: Admin & Manager
 * - View reviews: All users
 * 
 * Features:
 * - Submit product reviews
 * - Rate products (1-5 stars)
 * - Approve/reject reviews
 * - Mark reviews as helpful
 * - Filter reviews
 */
class ProductReviewController extends Controller
{
    /**
     * Display reviews for a product
     */
    public function index(Request $request, Product $product)
    {
        $query = ProductReview::where('product_id', $product->id)
            ->with(['customer', 'transaction']);

        // Filter by status (only admin/manager can see non-approved)
        if ($this->isAdminOrManager()) {
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
        } else {
            // Regular users only see approved reviews
            $query->approved();
        }

        // Filter by rating
        if ($request->has('rating')) {
            $query->rating($request->rating);
        }

        // Sort options
        $sortBy = $request->get('sort', 'recent');
        switch ($sortBy) {
            case 'helpful':
                $query->mostHelpful();
                break;
            case 'highest':
                $query->orderBy('rating', 'desc');
                break;
            case 'lowest':
                $query->orderBy('rating', 'asc');
                break;
            default: // recent
                $query->latest();
        }

        $reviews = $query->paginate(10)->withQueryString();

        // Calculate review statistics
        $stats = [
            'total_reviews' => ProductReview::where('product_id', $product->id)
                ->approved()
                ->count(),
            'average_rating' => ProductReview::where('product_id', $product->id)
                ->approved()
                ->avg('rating'),
            'rating_distribution' => [
                5 => ProductReview::where('product_id', $product->id)->approved()->rating(5)->count(),
                4 => ProductReview::where('product_id', $product->id)->approved()->rating(4)->count(),
                3 => ProductReview::where('product_id', $product->id)->approved()->rating(3)->count(),
                2 => ProductReview::where('product_id', $product->id)->approved()->rating(2)->count(),
                1 => ProductReview::where('product_id', $product->id)->approved()->rating(1)->count(),
            ],
        ];

        return Inertia::render('Products/Reviews/Index', [
            'product' => $product,
            'reviews' => $reviews,
            'stats' => $stats,
            'filters' => $request->only(['status', 'rating', 'sort']),
        ]);
    }

    /**
     * Show form to create a review
     * Customer must have purchased the product
     */
    public function create(Product $product)
    {
        $customerId = $this->getCurrentUser()->customer_id ?? null;

        // Check if user has purchased this product
        $hasPurchased = false;
        $transaction = null;

        if ($customerId) {
            $transaction = Transaction::where('customer_id', $customerId)
                ->where('status', 'completed')
                ->whereHas('items', function($query) use ($product) {
                    $query->where('product_id', $product->id);
                })
                ->whereDoesntHave('reviews', function($query) use ($product) {
                    $query->where('product_id', $product->id);
                })
                ->first();

            $hasPurchased = $transaction !== null;
        }

        // Check if already reviewed
        $existingReview = ProductReview::where('product_id', $product->id)
            ->where('customer_id', $customerId)
            ->first();

        return Inertia::render('Products/Reviews/Create', [
            'product' => $product,
            'hasPurchased' => $hasPurchased,
            'transaction' => $transaction,
            'existingReview' => $existingReview,
        ]);
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request, Product $product)
    {
        $customerId = $this->getCurrentUser()->customer_id ?? null;

        if (!$customerId) {
            return back()->withErrors([
                'error' => 'You must be a customer to leave a review.'
            ]);
        }

        // Validate
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:100',
            'comment' => 'required|string|max:1000',
            'transaction_id' => 'required|exists:transactions,id',
        ]);

        // Verify customer owns this transaction
        $transaction = Transaction::where('id', $validated['transaction_id'])
            ->where('customer_id', $customerId)
            ->where('status', 'completed')
            ->firstOrFail();

        // Check if already reviewed
        $existingReview = ProductReview::where('product_id', $product->id)
            ->where('customer_id', $customerId)
            ->first();

        if ($existingReview) {
            return back()->withErrors([
                'error' => 'You have already reviewed this product.'
            ]);
        }

        // Create review
        $review = ProductReview::create([
            'product_id' => $product->id,
            'customer_id' => $customerId,
            'transaction_id' => $validated['transaction_id'],
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'comment' => $validated['comment'],
            'status' => 'pending', // Reviews start as pending
        ]);

        return redirect()->route('products.show', $product)
            ->with('success', 'Thank you! Your review has been submitted and is pending approval.');
    }

    /**
     * Display a specific review
     */
    public function show(Product $product, ProductReview $review)
    {
        $review->load(['customer', 'transaction']);

        return Inertia::render('Products/Reviews/Show', [
            'product' => $product,
            'review' => $review,
        ]);
    }

    /**
     * Update a review
     * Only customer who created it or admin can update
     */
    public function update(Request $request, Product $product, ProductReview $review)
    {
        $customerId = $this->getCurrentUser()->customer_id ?? null;

        // Authorization check
        if (!($this->getCurrentUser()?->role?->name === 'admin') && $review->customer_id !== $customerId) {
            abort(403, 'You can only edit your own reviews.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:100',
            'comment' => 'required|string|max:1000',
        ]);

        $review->update([
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'comment' => $validated['comment'],
            'status' => 'pending', // Reset to pending after edit
        ]);

        return back()->with('success', 'Review updated successfully.');
    }

    /**
     * Delete a review
     * Only admin or review owner can delete
     */
    public function destroy(Product $product, ProductReview $review)
    {
        $customerId = $this->getCurrentUser()->customer_id ?? null;

        // Authorization check
        if (!($this->getCurrentUser()?->role?->name === 'admin') && $review->customer_id !== $customerId) {
            abort(403, 'You can only delete your own reviews.');
        }

        $review->delete();

        return redirect()->route('products.reviews.index', $product)
            ->with('success', 'Review deleted successfully.');
    }

    /**
     * Approve a review (Admin/Manager only)
     */
    public function approve(Product $product, ProductReview $review)
    {
        // Only admin/manager can approve
        if (!$this->isAdminOrManager()) {
            abort(403, 'Only administrators and managers can approve reviews.');
        }

        $review->approve();

        return back()->with('success', 'Review approved successfully.');
    }

    /**
     * Reject a review (Admin/Manager only)
     */
    public function reject(Product $product, ProductReview $review)
    {
        // Only admin/manager can reject
        if (!$this->isAdminOrManager()) {
            abort(403, 'Only administrators and managers can reject reviews.');
        }

        $review->reject();

        return back()->with('success', 'Review rejected.');
    }

    /**
     * Mark review as helpful
     */
    public function markHelpful(Product $product, ProductReview $review)
    {
        $review->markHelpful();

        return back()->with('success', 'Thank you for your feedback!');
    }

    /**
     * Mark review as not helpful
     */
    public function markNotHelpful(Product $product, ProductReview $review)
    {
        $review->markNotHelpful();

        return back()->with('success', 'Thank you for your feedback!');
    }

    /**
     * Admin dashboard for managing all reviews
     */
    public function manage(Request $request)
    {
        // Only admin/manager
        if (!$this->isAdminOrManager()) {
            abort(403);
        }

        $query = ProductReview::with(['product', 'customer', 'transaction']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search by product name or customer name
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('product', function($pq) use ($search) {
                    $pq->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('customer', function($cq) use ($search) {
                    $cq->where('name', 'like', "%{$search}%");
                });
            });
        }

        $reviews = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'pending' => ProductReview::pending()->count(),
            'approved' => ProductReview::approved()->count(),
            'rejected' => ProductReview::rejected()->count(),
        ];

        return Inertia::render('Admin/Reviews/Manage', [
            'reviews' => $reviews,
            'stats' => $stats,
            'filters' => $request->only(['status', 'search']),
        ]);
    }

    /**
     * Check if the current user is an admin or manager
     */
    private function isAdminOrManager()
    {
        $user = Auth::user();
        return $user && ($user->role?->name === 'admin' || $user->role?->name === 'manager');
    }

    /**
     * Get the current authenticated user
     */
    private function getCurrentUser()
    {
        return Auth::user();
    }
}