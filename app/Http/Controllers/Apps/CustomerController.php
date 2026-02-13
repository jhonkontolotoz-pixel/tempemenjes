<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sortField = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortField, $sortOrder);

        $customers = $query->paginate(10)->withQueryString();

        return Inertia::render('Apps/Customers', [
            'customers' => $customers,
            'filters'   => $request->only(['search', 'status']),
            'stats'     => [
                'total'    => Customer::count(),
                'active'   => Customer::where('status', 'active')->count(),
                'inactive' => Customer::where('status', 'inactive')->count(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'nullable|email|unique:customers,email',
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string',
            'city'        => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'status'      => 'required|in:active,inactive',
        ]);

        Customer::create($validated);

        return back()->with('success', 'Customer berhasil ditambahkan.');
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string',
            'city'        => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'status'      => 'required|in:active,inactive',
        ]);

        $customer->update($validated);

        return back()->with('success', 'Customer berhasil diupdate.');
    }

    public function destroy(Customer $customer)
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403, 'Hanya admin yang bisa menghapus customer.');
        }

        $customer->delete();

        return back()->with('success', 'Customer berhasil dihapus.');
    }
}
