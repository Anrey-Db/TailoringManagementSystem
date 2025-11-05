@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Payment Details</h2>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Payment Information</h5>
                <a href="{{ route('payments.receipt', $payment) }}" 
                   class="btn btn-outline-success"
                   target="_blank">
                    <i class="fas fa-receipt me-2"></i>Print Receipt
                </a>
            </div>
        </div>
        <div class="card-body">
            <p><strong>Order ID:</strong> {{ $payment->order_id }}</p>
            <p><strong>Amount:</strong> ₱{{ number_format($payment->amount_paid, 2) }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst($payment->payment_method) }}</p>
            <p><strong>Payment Date:</strong> {{ $payment->payment_date->format('F d, Y') }}</p>
            @if($payment->reference_number)
            <p><strong>Reference Number:</strong> {{ $payment->reference_number }}</p>
            @endif
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('payments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Payments
        </a>
    </div>
</div>
@endsection
