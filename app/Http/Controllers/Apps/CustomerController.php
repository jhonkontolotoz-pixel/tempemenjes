<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * CustomerController
 * 
 * Handles customer management operations.
 * Access: Admin & Manager only
 * 
 * Features:
 * - List all customers
 * - Create new customer
 * - View customer details
 * - Update customer information
 * - Delete customer (Admin only via frontend check)
 */
class CustomerController extends Controller
{
    /**
     * Display a listing of customers
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortField, $sortOrder);

        // Pagination
        $customers = $query->paginate(10)->withQueryString();

        return Inertia::render('Customers/Index', [
            'customers' => $customers,
            'filters' => $request->only(['search', 'status']),
            'stats' => [
                'total' => Customer::count(),
                'active' => Customer::where('status', 'active')->count(),
                'inactive' => Customer::where('status', 'inactive')->count(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        return Inertia::render('Customers/Create');
    }

    /**
     * Store a newly created customer
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'status' => 'required|in:active,inactive',
        ]);

        $customer = Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified customer
     */
    public function show(Customer $customer)
    {
        // Load related data if needed
        $customer->load(['orders' => function($query) {
            $query->latest()->take(10);
        }]);

        return Inertia::render('Customers/Show', [
            'customer' => $customer,
            'stats' => [
                'total_orders' => $customer->orders()->count(),
                'total_spent' => $customer->orders()->sum('total'),
                'last_order_date' => $customer->orders()->latest()->first()?->created_at,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified customer
     */
    public function edit(Customer $customer)
    {
        return Inertia::render('Customers/Edit', [
            'customer' => $customer,
        ]);
    }

    /**
     * Update the specified customer
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'status' => 'required|in:active,inactive',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer
     * Note: Delete action should be restricted to Admin only in frontend
     */
    public function destroy(Customer $customer)
    {
        // Additional check (optional, already protected by middleware)
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Only administrators can delete customers.');
        }

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}