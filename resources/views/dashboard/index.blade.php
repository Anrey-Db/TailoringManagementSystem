@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
            </h1>
            <p class="text-muted">Overview of your tailoring shop activities</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $totalCustomers }}</h4>
                            <p class="card-text">Total Customers</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $totalOrders }}</h4>
                            <p class="card-text">Total Orders</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clipboard-list fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">₱{{ number_format($totalRevenue, 2) }}</h4>
                            <p class="card-text">Total Revenue</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">₱{{ number_format($totalOutstanding, 2) }}</h4>
                            <p class="card-text">Outstanding Balance</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Orders -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Recent Orders
                    </h5>
                    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('orders.show', $order) }}" class="text-decoration-none">
                                                {{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td>{{ $order->customer->first_name }} {{ $order->customer->last_name }}</td>
                                        <td>{{ $order->order_type }}</td>
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
                                        <td>₱{{ number_format($order->total_amount, 2) }}</td>
                                        <td>{{ $order->order_date->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">No recent orders found.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Pending Payments
                    </h5>
                    <a href="{{ route('payments.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($pendingPayments->count() > 0)
                        @foreach($pendingPayments as $order)
                        <div class="d-flex justify-content-between align-items-center mb-3 p-2 border rounded">
                            <div>
                                <h6 class="mb-1">{{ $order->customer->first_name }} {{ $order->customer->last_name }}</h6>
                                <small class="text-muted">{{ $order->order_number }}</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-danger">₱{{ number_format($order->balance, 2) }}</div>
                                <small class="text-muted">{{ $order->order_date->format('M d') }}</small>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No pending payments.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status Overview -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Order Status Overview
                    </h5>
                </div>
                <div class="card-body">
                    @if(!empty($orderStatusCounts))
                        @foreach($orderStatusCounts as $status => $count)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ $status }}</span>
                            <div class="d-flex align-items-center">
                                <div class="progress me-2" style="width: 100px; height: 8px;">
                                    @php
                                        $percentage = $totalOrders > 0 ? ($count / $totalOrders) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar bg-{{ match($status) {
                                        'Pending' => 'warning',
                                        'In Progress' => 'info',
                                        'Completed' => 'success',
                                        'Cancelled' => 'danger',
                                        default => 'secondary'
                                    } }}" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="badge bg-secondary">{{ $count }}</span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No order data available.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Payment Status Overview
                    </h5>
                </div>
                <div class="card-body">
                    @if(!empty($paymentStatusCounts))
                        @foreach($paymentStatusCounts as $status => $count)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ $status }}</span>
                            <div class="d-flex align-items-center">
                                <div class="progress me-2" style="width: 100px; height: 8px;">
                                    @php
                                        $percentage = $totalOrders > 0 ? ($count / $totalOrders) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar bg-{{ match($status) {
                                        'Unpaid' => 'danger',
                                        'Partial' => 'warning',
                                        'Paid' => 'success',
                                        default => 'secondary'
                                    } }}" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="badge bg-secondary">{{ $count }}</span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No payment data available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
