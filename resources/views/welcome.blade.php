@extends('layouts.app')

@section('content')
<div class="text-center mt-5">
    <h1 class="display-5 fw-bold text-primary">  Welcome to Tailoring Management System</h1>
    <p class="lead mt-3 mb-4">
        Manage customers, measurements, orders, and payments with ease.
    </p>

    <a href="{{ route('customers.index') }}" class="btn btn-primary btn-lg me-2">Manage Customers</a>
    <a href="{{ route('measurements.index') }}" class="btn btn-success btn-lg me-2">Measurements</a>
    <a href="{{ route('orders.index') }}" class="btn btn-warning btn-lg me-2">Orders</a>
    <a href="{{ route('payments.index') }}" class="btn btn-danger btn-lg">Payments</a>
</div>
@endsection
