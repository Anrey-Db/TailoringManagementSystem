<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['order.customer']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('order', function($orderQuery) use ($search) {
                      $orderQuery->where('order_number', 'like', "%{$search}%")
                                ->orWhereHas('customer', function($customerQuery) use ($search) {
                                    $customerQuery->where('first_name', 'like', "%{$search}%")
                                                 ->orWhere('last_name', 'like', "%{$search}%");
                                });
                  });
            });
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->get('payment_method'));
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('payment_date', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('payment_date', '<=', $request->get('date_to'));
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'payment_date');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['payment_date', 'amount_paid', 'payment_method'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest('payment_date');
        }

        $payments = $query->paginate(10)->withQueryString();
        
        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create(Request $request)
    {
        $selectedOrderId = $request->get('order_id');
        
        // Get orders with outstanding balance
        $orders = Order::with('customer')
            ->where('balance', '>', 0)
            ->orderBy('order_number')
            ->get();
            
        return view('payments.create', compact('orders', 'selectedOrderId'));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|in:Cash,GCash',
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string|max:50',
        ]);

        DB::transaction(function () use ($validated) {
            // Ensure reference_number is not null
            if (empty($validated['reference_number'])) {
                $validated['reference_number'] = null;
            }
            
            // Create payment
            $payment = Payment::create($validated);
            
            // Update order payment status
            $order = Order::findOrFail($validated['order_id']);
            $totalPaid = $order->payments()->sum('amount_paid');
            $balance = $order->total_amount - $totalPaid;
            
            // Update order payment status and balance
            $paymentStatus = 'Partial';
            if ($balance <= 0) {
                $paymentStatus = 'Paid';
                $balance = 0;
            }
            
            $order->update([
                'amount_paid' => $totalPaid,
                'balance' => $balance,
                'payment_status' => $paymentStatus,
            ]);
        });

        return redirect()->route('payments.index')
                         ->with('success', 'Payment recorded successfully!');
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        $payment->load(['order.customer', 'order.orderItems']);
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(Payment $payment)
    {
        $orders = Order::with('customer')->orderBy('order_number')->get();
        return view('payments.edit', compact('payment', 'orders'));
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|in:Cash,GCash',
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'reference_number' => 'nullable|string|max:50',
        ]);

        DB::transaction(function () use ($validated, $payment) {
            // Ensure reference_number is not null
            if (empty($validated['reference_number'])) {
                $validated['reference_number'] = null;
            }
            
            // Update payment
            $payment->update($validated);
            
            // Update order payment status
            $order = Order::findOrFail($validated['order_id']);
            $totalPaid = $order->payments()->sum('amount_paid');
            $balance = $order->total_amount - $totalPaid;
            
            // Update order payment status and balance
            $paymentStatus = 'Partial';
            if ($balance <= 0) {
                $paymentStatus = 'Paid';
                $balance = 0;
            }
            
            $order->update([
                'amount_paid' => $totalPaid,
                'balance' => $balance,
                'payment_status' => $paymentStatus,
            ]);
        });

        return redirect()->route('payments.index')
                         ->with('success', 'Payment updated successfully!');
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Payment $payment)
    {
        DB::transaction(function () use ($payment) {
            $order = $payment->order;
            $payment->delete();
            
            // Recalculate order payment status
            $totalPaid = $order->payments()->sum('amount_paid');
            $balance = $order->total_amount - $totalPaid;
            
            $paymentStatus = 'Unpaid';
            if ($totalPaid > 0) {
                $paymentStatus = $balance > 0 ? 'Partial' : 'Paid';
            }
            
            $order->update([
                'amount_paid' => $totalPaid,
                'balance' => $balance,
                'payment_status' => $paymentStatus,
            ]);
        });

        return redirect()->route('payments.index')
                         ->with('success', 'Payment deleted successfully!');
    }

    /**
     * Generate receipt for payment
     */
    public function receipt(Payment $payment)
    {
        $payment->load(['order.customer', 'order.orderItems']);
        return view('payments.receipt', compact('payment'));
    }
}
