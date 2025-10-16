<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Measurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'measurement', 'measurement.items.units'])
            ->whereNotNull('measurement_id'); // Show only orders with a linked measurement

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('first_name', 'like', "%{$search}%")
                                   ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->get('payment_status'));
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['order_number', 'order_date', 'due_date', 'status', 'total_amount'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        $orders = $query->paginate(10)->withQueryString();
        
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create(Request $request)
    {
        $customers = Customer::orderBy('first_name')->get();
        $measurements = Measurement::with(['customer', 'items'])->orderBy('created_at', 'desc')->get();
        
        return view('orders.create', compact('customers', 'measurements'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'measurement_id' => 'nullable|exists:measurements,id',
            'due_date' => 'nullable|date|after_or_equal:today',
            'status' => 'required|in:Pending,In Progress,Completed,Delivered',
            'remarks' => 'nullable|string|max:500',
        ]);

        $order = null;
        DB::transaction(function () use ($validated, &$order) {
            // Generate unique order number
            do {
                $orderNumber = 'ORD-' . date('Y') . '-' . str_pad(Order::count() + 1, 4, '0', STR_PAD_LEFT);
            } while (Order::where('order_number', $orderNumber)->exists());
            
            // Calculate total amount
            $totalAmount = 0;
            if ($validated['measurement_id']) {
                $measurement = Measurement::with('items')->find($validated['measurement_id']);
                $totalAmount = $measurement->items->sum('total_price');
            }
            
            // Create order
            $order = Order::create([
                'customer_id' => $validated['customer_id'],
                'measurement_id' => $validated['measurement_id'],
                'order_number' => $orderNumber,
                'order_date' => now(),
                'due_date' => $validated['due_date'],
                'status' => $validated['status'],
                'total_amount' => $totalAmount,
                'amount_paid' => 0,
                'balance' => $totalAmount,
                'payment_status' => 'Unpaid',
                'remarks' => $validated['remarks'],
            ]);
        });

        return redirect()->route('orders.index')
            ->with('success', 'Order created successfully!');
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['customer', 'measurement.items.units', 'payments']);
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        // Orders are read-only - redirect to measurement edit
        return redirect()->route('measurements.edit', $order->measurement_id)
            ->with('info', 'Orders are read-only. Edit the measurement to update order details.');
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
        // Only allow status and due date updates
        $validated = $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed,Delivered',
            'due_date' => 'nullable|date',
        ]);

        $order->update($validated);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order status updated successfully!');
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        // Orders are read-only - redirect to measurement
        return redirect()->route('measurements.show', $order->measurement_id)
            ->with('info', 'Orders are read-only. Delete the measurement to remove the order.');
    }

    /**
     * Generate order from measurement
     */
    public function generateFromMeasurement(Measurement $measurement)
    {
        // Check if order already exists for this measurement
        $existingOrder = Order::where('measurement_id', $measurement->id)->first();
        if ($existingOrder) {
            return redirect()->route('orders.show', $existingOrder)
                ->with('info', 'Order already exists for this measurement.');
        }

        DB::transaction(function () use ($measurement) {
            // Generate order number
            $orderNumber = 'ORD-' . date('Y') . '-' . str_pad(Order::count() + 1, 4, '0', STR_PAD_LEFT);
            
            // Calculate total from measurement items
            $totalAmount = $measurement->items->sum('total_price');
            
            // Create order
            $order = Order::create([
                'customer_id' => $measurement->customer_id,
                'measurement_id' => $measurement->id,
                'order_number' => $orderNumber,
                'order_date' => now(),
                'due_date' => null,
                'status' => 'Pending',
                'total_amount' => $totalAmount,
                'amount_paid' => 0,
                'balance' => $totalAmount,
                'payment_status' => 'Unpaid',
                'remarks' => 'Generated from measurement #' . $measurement->id,
            ]);
        });

        return redirect()->route('orders.index')
            ->with('success', 'Order generated successfully from measurement!');
    }

    /**
     * Print order summary/receipt
     */
    public function print(Order $order)
    {
        $order->load(['customer', 'measurement.items.units', 'payments']);
        return view('orders.print', compact('order'));
    }
}