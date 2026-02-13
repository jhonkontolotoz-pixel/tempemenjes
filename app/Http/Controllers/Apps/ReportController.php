<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->toDateString());
        $dateTo   = $request->get('date_to', Carbon::now()->toDateString());

        $salesData = Transaction::where('status', 'completed')
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total_transactions, SUM(total) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $stats = [
            'total_revenue'      => Transaction::where('status', 'completed')
                ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                ->sum('total'),
            'total_transactions' => Transaction::where('status', 'completed')
                ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                ->count(),
            'total_customers'    => Transaction::where('status', 'completed')
                ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                ->distinct('customer_id')
                ->count('customer_id'),
            'avg_transaction'    => Transaction::where('status', 'completed')
                ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                ->avg('total') ?? 0,
        ];

        $paymentMethods = Transaction::where('status', 'completed')
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total) as total')
            ->groupBy('payment_method')
            ->get();

        return Inertia::render('Apps/Reports', [
            'salesData'      => $salesData,
            'stats'          => $stats,
            'paymentMethods' => $paymentMethods,
            'filters'        => ['date_from' => $dateFrom, 'date_to' => $dateTo],
        ]);
    }
}
