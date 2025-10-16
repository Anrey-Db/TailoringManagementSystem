@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-user me-2"></i>Customer Details
                    </h1>
                    <p class="text-muted">View customer information, measurements, and order history</p>
                </div>
                <div>
                    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i>Back to List
                    </a>
                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-1"></i>Edit Customer
                    </a>
                    <a href="{{ route('measurements.create', ['customer_id' => $customer->id]) }}" class="btn btn-primary me-2">
                        <i class="fas fa-ruler me-1"></i>Add Measurement
                    </a>
                    <a href="{{ route('orders.create', ['customer_id' => $customer->id]) }}" class="btn btn-success">
                        <i class="fas fa-plus me-1"></i>New Order
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customer Information -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i>Customer Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="avatar-lg bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center">
                            {{ strtoupper(substr($customer->first_name, 0, 1)) }}{{ strtoupper(substr($customer->last_name, 0, 1)) }}
                        </div>
                        <h4 class="mt-2 mb-0">{{ $customer->first_name }} {{ $customer->last_name }}</h4>
                        <p class="text-muted">Customer since {{ $customer->created_at->format('M Y') }}</p>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Contact Number</label>
                            <p class="mb-0">
                                @if($customer->contact_number)
                                    <a href="tel:{{ $customer->contact_number }}" class="text-decoration-none">
                                        <i class="fas fa-phone me-1"></i>{{ $customer->contact_number }}
                                    </a>
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Email</label>
                            <p class="mb-0">
                                @if($customer->email)
                                    <a href="mailto:{{ $customer->email }}" class="text-decoration-none">
                                        <i class="fas fa-envelope me-1"></i>{{ $customer->email }}
                                    </a>
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Address</label>
                            <p class="mb-0">
                                @if($customer->address)
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $customer->address }}
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </p>
                        </div>
                        @if($customer->notes)
                        <div class="col-12">
                            <label class="form-label fw-bold">Notes</label>
                            <p class="mb-0">{{ $customer->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Measurements -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-ruler me-2"></i>Measurements
                        <span class="badge bg-primary ms-2">{{ $customer->measurements->count() }}</span>
                    </h5>
                    <a href="{{ route('measurements.create', ['customer_id' => $customer->id]) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>Add Measurement
                    </a>
                </div>
                <div class="card-body">
                    @if($customer->measurements->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Date Measured</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->measurements as $measurement)
                                    <tr>
                                        <td>
                                            <span class="badge bg-info">{{ $measurement->measurement_type }}</span>
                                        </td>
                                        <td>{{ $measurement->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('measurements.show', $measurement) }}" 
                                                   class="btn btn-sm btn-outline-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('measurements.edit', $measurement) }}" 
                                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-ruler fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No measurements recorded</h5>
                            <p class="text-muted">Add measurements for this customer to get started.</p>
                            <a href="{{ route('measurements.create', ['customer_id' => $customer->id]) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Add First Measurement
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Order History -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>Order History
                        <span class="badge bg-primary ms-2">{{ $customer->orders->count() }}</span>
                    </h5>
                    <a href="{{ route('orders.create', ['customer_id' => $customer->id]) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus me-1"></i>New Order
                    </a>
                </div>
                <div class="card-body">
                    @if($customer->orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Payment Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->orders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('orders.show', $order) }}" class="text-decoration-none fw-bold">
                                                {{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td>{{ $order->order_type }}</td>
                                        <td>{{ $order->quantity }}</td>
                                        <td>₱{{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            @php
                                                $statusClass = match($order->status) {
                                                    'Pending' => 'warning',
                                                    'In Progress' => 'info',
                                                    'Completed' => 'success',
                                                    'Cancelled' => 'danger',
                                                    default => 'secondary'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}">{{ $order->status }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $paymentClass = match($order->payment_status) {
                                                    'Unpaid' => 'danger',
                                                    'Partial' => 'warning',
                                                    'Paid' => 'success',
                                                    default => 'secondary'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $paymentClass }}">{{ $order->payment_status }}</span>
                                        </td>
                                        <td>{{ $order->order_date->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('orders.show', $order) }}" 
                                                   class="btn btn-sm btn-outline-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('orders.edit', $order) }}" 
                                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No orders found</h5>
                            <p class="text-muted">This customer hasn't placed any orders yet.</p>
                            <a href="{{ route('orders.create', ['customer_id' => $customer->id]) }}" class="btn btn-success">
                                <i class="fas fa-plus me-1"></i>Create First Order
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-lg {
    width: 80px;
    height: 80px;
    font-size: 32px;
}
</style>
@endsection
