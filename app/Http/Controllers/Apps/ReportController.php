<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

/**
 * ReportController
 * 
 * Handles reporting and analytics.
 * Access: Admin & Manager only
 * 
 * Features:
 * - Sales reports
 * - Product performance
 * - Customer analytics
 * - Export to Excel/PDF
 */
class ReportController extends Controller
{
    /**
     * Display main reports dashboard
     */
    public function index(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', Carbon::now()->toDateString());

        // Sales Overview
        $salesData = Transaction::where('status', 'completed')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('DATE(created_at) as date')
            ->selectRaw('COUNT(*) as total_transactions')
            ->selectRaw('SUM(total) as revenue')
            ->selectRaw('SUM(subtotal - (SELECT SUM(cost * quantity) FROM transaction_items WHERE transaction_id = transactions.id)) as profit')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top Products
        $topProducts = Product::withCount(['orderItems as total_sold' => function($query) use ($dateFrom, $dateTo) {
                $query->selectRaw('SUM(quantity)')
                    ->whereHas('transaction', function($q) use ($dateFrom, $dateTo) {
                        $q->where('status', 'completed')
                          ->whereBetween('created_at', [$dateFrom, $dateTo]);
                    });
            }])
            ->having('total_sold', '>', 0)
            ->orderBy('total_sold', 'desc')
            ->take(10)
            ->get();

        // Top Customers
        $topCustomers = Customer::withSum(['transactions as total_spent' => function($query) use ($dateFrom, $dateTo) {
                $query->where('status', 'completed')
                    ->whereBetween('created_at', [$dateFrom, $dateTo]);
            }], 'total')
            ->withCount(['transactions as total_orders' => function($query) use ($dateFrom, $dateTo) {
                $query->where('status', 'completed')
                    ->whereBetween('created_at', [$dateFrom, $dateTo]);
            }])
            ->having('total_spent', '>', 0)
            ->orderBy('total_spent', 'desc')
            ->take(10)
            ->get();

        // Summary Statistics
        $stats = [
            'total_revenue' => Transaction::where('status', 'completed')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->sum('total'),
            'total_transactions' => Transaction::where('status', 'completed')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->count(),
            'total_customers' => Transaction::where('status', 'completed')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->distinct('customer_id')
                ->count('customer_id'),
            'avg_transaction' => Transaction::where('status', 'completed')
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->avg('total'),
        ];

        // Payment Methods Breakdown
        $paymentMethods = Transaction::where('status', 'completed')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total) as total')
            ->groupBy('payment_method')
            ->get();

        return Inertia::render('Reports/Index', [
            'salesData' => $salesData,
            'topProducts' => $topProducts,
            'topCustomers' => $topCustomers,
            'stats' => $stats,
            'paymentMethods' => $paymentMethods,
            'filters' => [
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
        ]);
    }

    /**
     * Sales Report
     */
    public function sales(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', Carbon::now()->toDateString());
        $groupBy = $request->get('group_by', 'day'); // day, week, month

        $query = Transaction::where('status', 'completed')
            ->whereBetween('created_at', [$dateFrom, $dateTo]);

        // Group by period
        switch ($groupBy) {
            case 'week':
                $salesData = $query->selectRaw('YEARWEEK(created_at) as period')
                    ->selectRaw('COUNT(*) as transactions')
                    ->selectRaw('SUM(total) as revenue')
                    ->selectRaw('SUM(tax) as tax')
                    ->selectRaw('SUM(discount) as discount')
                    ->groupBy('period')
                    ->get();
                break;
            case 'month':
                $salesData = $query->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as period')
                    ->selectRaw('COUNT(*) as transactions')
                    ->selectRaw('SUM(total) as revenue')
                    ->selectRaw('SUM(tax) as tax')
                    ->selectRaw('SUM(discount) as discount')
                    ->groupBy('period')
                    ->get();
                break;
            default: // day
                $salesData = $query->selectRaw('DATE(created_at) as period')
                    ->selectRaw('COUNT(*) as transactions')
                    ->selectRaw('SUM(total) as revenue')
                    ->selectRaw('SUM(tax) as tax')
                    ->selectRaw('SUM(discount) as discount')
                    ->groupBy('period')
                    ->get();
        }

        return Inertia::render('Reports/Sales', [
            'salesData' => $salesData,
            'filters' => $request->only(['date_from', 'date_to', 'group_by']),
        ]);
    }

    /**
     * Product Performance Report
     */
    public function products(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', Carbon::now()->toDateString());

        $products = Product::with('category')
            ->withCount(['orderItems as total_sold' => function($query) use ($dateFrom, $dateTo) {
                $query->selectRaw('SUM(quantity)')
                    ->whereHas('transaction', function($q) use ($dateFrom, $dateTo) {
                        $q->where('status', 'completed')
                          ->whereBetween('created_at', [$dateFrom, $dateTo]);
                    });
            }])
            ->withSum(['orderItems as revenue' => function($query) use ($dateFrom, $dateTo) {
                $query->whereHas('transaction', function($q) use ($dateFrom, $dateTo) {
                    $q->where('status', 'completed')
                      ->whereBetween('created_at', [$dateFrom, $dateTo]);
                });
            }], 'subtotal')
            ->orderBy('total_sold', 'desc')
            ->paginate(20);

        return Inertia::render('Reports/Products', [
            'products' => $products,
            'filters' => $request->only(['date_from', 'date_to']),
        ]);
    }

    /**
     * Customer Analytics Report
     */
    public function customers(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', Carbon::now()->toDateString());

        $customers = Customer::withSum(['transactions as total_spent' => function($query) use ($dateFrom, $dateTo) {
                $query->where('status', 'completed')
                    ->whereBetween('created_at', [$dateFrom, $dateTo]);
            }], 'total')
            ->withCount(['transactions as total_orders' => function($query) use ($dateFrom, $dateTo) {
                $query->where('status', 'completed')
                    ->whereBetween('created_at', [$dateFrom, $dateTo]);
            }])
            ->orderBy('total_spent', 'desc')
            ->paginate(20);

        $stats = [
            'total_customers' => Customer::count(),
            'active_customers' => Customer::whereHas('transactions', function($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('created_at', [$dateFrom, $dateTo]);
            })->count(),
            'new_customers' => Customer::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
        ];

        return Inertia::render('Reports/Customers', [
            'customers' => $customers,
            'stats' => $stats,
            'filters' => $request->only(['date_from', 'date_to']),
        ]);
    }

    /**
     * Export report to Excel
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'sales'); // sales, products, customers
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', Carbon::now()->toDateString());

        // Implement Excel export using Laravel Excel package
        // return Excel::download(new SalesReportExport($dateFrom, $dateTo), 'sales-report.xlsx');

        // For now, return JSON (you can implement Excel export later)
        return response()->json([
            'message' => 'Export functionality - Install maatwebsite/excel package to enable this feature',
            'type' => $type,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ]);
    }
}