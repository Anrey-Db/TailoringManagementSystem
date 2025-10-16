@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>Order Details
                    </h1>
                    <p class="text-muted">View order information and manage status</p>
                </div>
                <div>
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i>Back to Orders
                    </a>
                    <a href="{{ route('orders.print', $order) }}" class="btn btn-success me-2" target="_blank">
                        <i class="fas fa-print me-1"></i>Print Receipt
                    </a>
                    <a href="{{ route('measurements.show', $order->measurement) }}" class="btn btn-info">
                        <i class="fas fa-ruler me-1"></i>View Measurement
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Order Information -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Order Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Order Number</label>
                            <p class="mb-0">{{ $order->order_number }}</p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Customer</label>
                            <p class="mb-0">
                                <a href="{{ route('customers.show', $order->customer) }}" class="text-decoration-none">
                                    {{ $order->customer->first_name }} {{ $order->customer->last_name }}
                                </a>
                            </p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Measurement</label>
                            <p class="mb-0">
                                <a href="{{ route('measurements.show', $order->measurement) }}" class="text-decoration-none">
                                    #{{ $order->measurement->id }} - {{ $order->measurement->measurement_type }}
                                </a>
                            </p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Order Date</label>
                            <p class="mb-0">{{ $order->order_date->format('M d, Y') }}</p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Total Amount</label>
                            <p class="mb-0 h5 text-success">₱{{ number_format($order->total_amount, 2) }}</p>
                        </div>
                        @if($order->balance > 0)
                        <div class="col-12">
                            <label class="form-label fw-bold">Outstanding Balance</label>
                            <p class="mb-0 h6 text-danger">₱{{ number_format($order->balance, 2) }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Management -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-cog me-2"></i>Status Management
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Order Status</label>
                            <select name="status" class="form-select">
                                <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="In Progress" {{ $order->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Delivered" {{ $order->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Due Date</label>
                            <input type="date" name="due_date" class="form-control" value="{{ $order->due_date ? $order->due_date->format('Y-m-d') : '' }}">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-1"></i>Update Status
                        </button>
                    </form>

                    <hr>

                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label fw-bold">Payment Status</label>
                            <p class="mb-0">
                                @php
                                    $paymentClass = match($order->payment_status) {
                                        'Unpaid' => 'danger',
                                        'Partial' => 'warning',
                                        'Paid' => 'success',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $paymentClass }}">{{ $order->payment_status }}</span>
                            </p>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold">Amount Paid</label>
                            <p class="mb-0">₱{{ number_format($order->amount_paid, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Actions -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>Payment Actions
                    </h5>
                </div>
                <div class="card-body">
                    @if($order->balance > 0)
                        <a href="{{ route('payments.create', ['order_id' => $order->id]) }}" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-plus me-1"></i>Record Payment
                        </a>
                    @endif
                    
                    <a href="{{ route('payments.index', ['search' => $order->order_number]) }}" class="btn btn-outline-info w-100">
                        <i class="fas fa-list me-1"></i>View Payment History
                    </a>

                    @if($order->payments->count() > 0)
                    <hr>
                    <h6>Recent Payments</h6>
                    @foreach($order->payments->take(3) as $payment)
                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                        <div>
                            <small class="text-muted">{{ $payment->payment_date->format('M d') }}</small>
                            <div class="small">{{ $payment->payment_method }}</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">₱{{ number_format($payment->amount_paid, 2) }}</div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Order Items
                    </h5>
                </div>
                <div class="card-body">
                    @if($order->measurement && $order->measurement->items->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Item Type</th>
                                        <th>Quantity</th>
                                        <th>Price per Item</th>
                                        <th>Total Price</th>
                                        <th>Per-Person Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->measurement->items as $item)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ $item->item_type }}</span>
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>₱{{ number_format($item->price_per_item, 2) }}</td>
                                        <td class="fw-bold">₱{{ number_format($item->total_price, 2) }}</td>
                                        <td>
                                            @if($item->units->count() > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-striped mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Name</th>
                                                                <th>Size</th>
                                                                <th>Chest</th>
                                                                <th>Waist</th>
                                                                <th>Hip</th>
                                                                <th>Length</th>
                                                                <th>Unit Price</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($item->units as $i => $unit)
                                                            <tr>
                                                                <td>{{ $i + 1 }}</td>
                                                                <td>{{ $unit->person_name }}</td>
                                                                <td>{{ $unit->size_label }}</td>
                                                                <td>{{ $unit->chest }}</td>
                                                                <td>{{ $unit->waist }}</td>
                                                                <td>{{ $unit->hip }}</td>
                                                                <td>{{ $unit->length }}</td>
                                                                <td>₱{{ number_format($unit->unit_price, 2) }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <span class="text-muted">No per-person details</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-success">
                                        <th colspan="3">Total Amount</th>
                                        <th class="h5">₱{{ number_format($order->total_amount, 2) }}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No items found</h5>
                            <p class="text-muted">This order has no items associated with it.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection