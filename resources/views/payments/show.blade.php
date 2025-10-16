@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Payment Details</h2>

    <div class="card">
        <div class="card-body">
            <p><strong>Order ID:</strong> {{ $payment->order_id }}</p>
            <p><strong>Amount:</strong> ₱{{ number_format($payment->amount, 2) }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst($payment->payment_method) }}</p>
            <p><strong>Payment Date:</strong> {{ $payment->payment_date }}</p>
        </div>
    </div>

    <a href="{{ route('payments.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection
