@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-credit-card me-2"></i>Payment Management
                    </h1>
                    <p class="text-muted">Track customer payments and outstanding balances</p>
                </div>
                <a href="{{ route('payments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Record Payment
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
            <form method="GET" action="{{ route('payments.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Search Payments</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search by reference, order, or customer...">
                    </div>
                </div>
                <div class="col-md-2">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    <select class="form-select" id="payment_method" name="payment_method">
                        <option value="">All Methods</option>
                        <option value="Cash" {{ request('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="GCash" {{ request('payment_method') == 'GCash' ? 'selected' : '' }}>GCash</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date_from" class="form-label">Date From</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label for="date_to" class="form-label">Date To</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" 
                           value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <label for="sort_by" class="form-label">Sort By</label>
                    <select class="form-select" id="sort_by" name="sort_by">
                        <option value="payment_date" {{ request('sort_by') == 'payment_date' ? 'selected' : '' }}>Payment Date</option>
                        <option value="amount_paid" {{ request('sort_by') == 'amount_paid' ? 'selected' : '' }}>Amount</option>
                        <option value="payment_method" {{ request('sort_by') == 'payment_method' ? 'selected' : '' }}>Method</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label for="sort_order" class="form-label">Order</label>
                    <select class="form-select" id="sort_order" name="sort_order">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Desc</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Asc</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="fas fa-filter me-1"></i>Apply Filters
                    </button>
                    <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Clear Filters
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Payments List
                <span class="badge bg-primary ms-2">{{ $payments->total() }}</span>
            </h5>
        </div>
        <div class="card-body">
            @if($payments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Payment #</th>
                                <th>Order</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Reference</th>
                                <th>Payment Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                            <tr>
                                <td>
                                    <a href="{{ route('payments.show', $payment) }}" class="text-decoration-none fw-bold">
                                        #{{ $payment->id }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('orders.show', $payment->order) }}" class="text-decoration-none">
                                        {{ $payment->order->order_number }}
                                    </a>
                                    <div class="small text-muted">
                                        Total: ₱{{ number_format($payment->order->measurement->items->sum('total_price'), 2) }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            {{ strtoupper(substr($payment->order->customer->first_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">
                                                <a href="{{ route('customers.show', $payment->order->customer) }}" class="text-decoration-none">
                                                    {{ $payment->order->customer->first_name }} {{ $payment->order->customer->last_name }}
                                                </a>
                                            </div>
                                            @if($payment->order->customer->contact_number)
                                                <small class="text-muted">{{ $payment->order->customer->contact_number }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-success">₱{{ number_format($payment->amount_paid, 2) }}</div>
                                    @if($payment->order->balance > 0)
                                        <small class="text-danger">Balance: ₱{{ number_format($payment->order->balance, 2) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $payment->payment_method == 'Cash' ? 'success' : 'info' }}">
                                        {{ $payment->payment_method }}
                                    </span>
                                </td>
                                <td>
                                    @if($payment->reference_number)
                                        <code>{{ $payment->reference_number }}</code>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('payments.show', $payment) }}" 
                                           class="btn btn-sm btn-outline-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('payments.receipt', $payment) }}" 
                                           class="btn btn-sm btn-outline-success" title="Print Receipt">
                                            <i class="fas fa-receipt"></i>
                                        </a>
                                        <a href="{{ route('payments.edit', $payment) }}" 
                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('payments.destroy', $payment) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this payment?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
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
                        Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} 
                        of {{ $payments->total() }} payments
                    </div>
                    <div>
                        {{ $payments->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No payments found</h5>
                    <p class="text-muted">
                        @if(request('search') || request('payment_method') || request('date_from'))
                            No payments match your search criteria.
                        @else
                            Get started by recording your first payment.
                        @endif
                    </p>
                    <a href="{{ route('payments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Record First Payment
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
