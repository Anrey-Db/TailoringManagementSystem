<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Measurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with overview statistics.
     */
    public function index()
    {
        // Get total counts
        $totalCustomers = Customer::count();
        $totalOrders = Order::count();
        $totalPayments = Payment::count();
        $totalMeasurements = Measurement::count();

        // Get recent orders
        $recentOrders = Order::with('customer')
            ->latest()
            ->take(5)
            ->get();

        // Get pending payments (orders with balance > 0)
        $pendingPayments = Order::with('customer')
            ->where('balance', '>', 0)
            ->latest()
            ->take(5)
            ->get();

        // Get order status counts
        $orderStatusCounts = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Get payment status counts
        $paymentStatusCounts = Order::select('payment_status', DB::raw('count(*) as count'))
            ->groupBy('payment_status')
            ->pluck('count', 'payment_status')
            ->toArray();

        // Get monthly revenue (last 6 months)
        $monthlyRevenue = Payment::select(
                DB::raw('DATE_FORMAT(payment_date, "%Y-%m") as month'),
                DB::raw('SUM(amount_paid) as total')
            )
            ->where('payment_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Get total revenue
        $totalRevenue = Payment::sum('amount_paid');
        $totalOutstanding = Order::sum('balance');

        return view('dashboard.index', compact(
            'totalCustomers',
            'totalOrders',
            'totalPayments',
            'totalMeasurements',
            'recentOrders',
            'pendingPayments',
            'orderStatusCounts',
            'paymentStatusCounts',
            'monthlyRevenue',
            'totalRevenue',
            'totalOutstanding'
        ));
    }
}
