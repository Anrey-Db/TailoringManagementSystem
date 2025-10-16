@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>Order Management
                    </h1>
                    <p class="text-muted">Manage customer orders and track their progress</p>
                </div>
                <a href="{{ route('orders.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Create New Order
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search and Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('orders.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search Orders</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search by order number or customer name...">
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="payment_status" class="form-label">Payment Status</label>
                    <select class="form-select" id="payment_status" name="payment_status">
                        <option value="">All Payments</option>
                        <option value="Unpaid" {{ request('payment_status') == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="Partial" {{ request('payment_status') == 'Partial' ? 'selected' : '' }}>Partial</option>
                        <option value="Paid" {{ request('payment_status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="sort_by" class="form-label">Sort By</label>
                    <select class="form-select" id="sort_by" name="sort_by">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                        <option value="order_date" {{ request('sort_by') == 'order_date' ? 'selected' : '' }}>Order Date</option>
                        <option value="due_date" {{ request('sort_by') == 'due_date' ? 'selected' : '' }}>Due Date</option>
                        <option value="total_amount" {{ request('sort_by') == 'total_amount' ? 'selected' : '' }}>Amount</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="sort_order" class="form-label">Order</label>
                    <select class="form-select" id="sort_order" name="sort_order">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="fas fa-filter me-1"></i>Apply Filters
                    </button>
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Clear Filters
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Orders List
                <span class="badge bg-primary ms-2">{{ $orders->total() }}</span>
            </h5>
        </div>
        <div class="card-body">
            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
        <thead>
            <tr>
                                <th>Order #</th>
                <th>Customer</th>
                                <th>Measurement</th>
                                <th>Items</th>
                <th>Order Date</th>
                <th>Status</th>
                                <th>Payment Status</th>
                <th>Total Amount</th>
                                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('orders.show', $order) }}" class="text-decoration-none fw-bold">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            {{ strtoupper(substr($order->customer->first_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">
                                                <a href="{{ route('customers.show', $order->customer) }}" class="text-decoration-none">
                                                    {{ $order->customer->first_name }} {{ $order->customer->last_name }}
                                                </a>
                                            </div>
                                            @if($order->customer->contact_number)
                                                <small class="text-muted">{{ $order->customer->contact_number }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($order->measurement)
                                        <div>
                                            <span class="badge bg-info">{{ $order->measurement->measurement_type }}</span>
                                            <div class="small text-muted">#{{ $order->measurement->id }}</div>
                                        </div>
                                    @else
                                        <span class="text-muted">No measurement</span>
                                    @endif
                                </td>
                                <td>
                                    @if($order->measurement && $order->measurement->items->count() > 0)
                                        <div class="small">
                                            @foreach($order->measurement->items->take(2) as $item)
                                                <div>
                                                    <span class="badge bg-light text-dark">{{ $item->item_type }}</span>
                                                    <span class="text-muted">x{{ $item->quantity }}</span>
                                                </div>
                                            @endforeach
                                            @if($order->measurement->items->count() > 2)
                                                <div class="text-muted">+{{ $order->measurement->items->count() - 2 }} more</div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">No items</span>
                                    @endif
                                </td>
                                <td>{{ $order->order_date->format('M d, Y') }}</td>
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
                                <td>
                                    <div class="fw-bold">₱{{ number_format($order->total_amount, 2) }}</div>
                                    @if($order->balance > 0)
                                        <small class="text-danger">Balance: ₱{{ number_format($order->balance, 2) }}</small>
                                    @endif
                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('orders.show', $order) }}" 
                                           class="btn btn-sm btn-outline-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('orders.print', $order) }}" 
                                           class="btn btn-sm btn-outline-success" title="Print Receipt" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                </td>
            </tr>
                            @endforeach
        </tbody>
    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} 
                        of {{ $orders->total() }} orders
                    </div>
                    <div>
    {{ $orders->links() }}
</div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No orders found</h5>
                    <p class="text-muted">
                        @if(request('search') || request('status') || request('payment_status'))
                            No orders match your search criteria.
                        @else
                            Get started by creating your first order.
                        @endif
                    </p>
                    <a href="{{ route('orders.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create First Order
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
}
</style>
@endsection
