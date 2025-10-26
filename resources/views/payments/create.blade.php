@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-credit-card me-2"></i>Add Payment
                    </h1>
                    <p class="text-muted">Record payment for customer orders</p>
                </div>
                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Payments
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

    <form action="{{ route('payments.store') }}" method="POST">
        @csrf

        <div class="row">
            <!-- Payment Details -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Payment Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="order_id" class="form-label">Customer & Order <span class="text-danger">*</span></label>
                            <select name="order_id" id="order_id" class="form-select @error('order_id') is-invalid @enderror" required>
                                <option value="">Select Customer Order</option>
                                @foreach($orders as $order)
                                    <option value="{{ $order->id }}" 
                                            data-balance="{{ $order->measurement->items->sum('total_price') - $order->amount_paid }}"
                                            data-total="{{ $order->measurement->items->sum('total_price') }}"
                                            data-paid="{{ $order->amount_paid }}"
                                            {{ $selectedOrderId == $order->id ? 'selected' : '' }}>
                                        {{ $order->customer->first_name }} {{ $order->customer->last_name }} 
                                        - Order #{{ $order->order_number }}
                                        (Balance: ₱{{ number_format($order->balance, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('order_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Select the customer and order to record payment for</div>
                        </div>

                        <div class="mb-3">
                            <label for="amount_paid" class="form-label">Amount Paid <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" step="0.01" name="amount_paid" id="amount_paid" 
                                       class="form-control @error('amount_paid') is-invalid @enderror" 
                                       placeholder="0.00" required>
                            </div>
                            @error('amount_paid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                            <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                <option value="">Select Payment Method</option>
                                <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                <option value="GCash" {{ old('payment_method') == 'GCash' ? 'selected' : '' }}>GCash</option>
                                <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="Check" {{ old('payment_method') == 'Check' ? 'selected' : '' }}>Check</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" 
                                   class="form-control @error('payment_date') is-invalid @enderror" 
                                   value="{{ old('payment_date', date('Y-m-d')) }}" required>
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="reference_number" class="form-label">Reference Number</label>
                            <input type="text" name="reference_number" 
                                   class="form-control @error('reference_number') is-invalid @enderror" 
                                   placeholder="Transaction reference or check number" 
                                   value="{{ old('reference_number') }}">
                            @error('reference_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optional: Receipt number, transaction ID, or check number</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-calculator me-2"></i>Order Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="order-summary">
                            <div class="text-center py-4">
                                <i class="fas fa-info-circle fa-2x text-muted mb-3"></i>
                                <h6 class="text-muted">Select an order to view details</h6>
                            </div>
                        </div>

                        <div id="order-details" style="display: none;">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label fw-bold">Total Amount:</label>
                                </div>
                                <div class="col-6 text-end">
                                    <span id="total-amount" class="h6">₱0.00</span>
                                </div>
                                
                                <div class="col-6">
                                    <label class="form-label fw-bold">Amount Paid:</label>
                                </div>
                                <div class="col-6 text-end">
                                    <span id="amount-paid" class="h6 text-info">₱0.00</span>
                                </div>
                                
                                <div class="col-6">
                                    <label class="form-label fw-bold">Outstanding Balance:</label>
                                </div>
                                <div class="col-6 text-end">
                                    <span id="outstanding-balance" class="h6 text-danger">₱0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i>Record Payment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const orderSelect = document.getElementById('order_id');
    const amountPaidInput = document.getElementById('amount_paid');
    const orderSummary = document.getElementById('order-summary');
    const orderDetails = document.getElementById('order-details');
    const totalAmount = document.getElementById('total-amount');
    const amountPaid = document.getElementById('amount-paid');
    const outstandingBalance = document.getElementById('outstanding-balance');

    orderSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value === '') {
            orderSummary.style.display = 'block';
            orderDetails.style.display = 'none';
            return;
        }

        const total = parseFloat(selectedOption.getAttribute('data-total')) || 0;
        const paid = parseFloat(selectedOption.getAttribute('data-paid')) || 0;
        const balance = parseFloat(selectedOption.getAttribute('data-balance')) || 0;

        // Show order details
        orderSummary.style.display = 'none';
        orderDetails.style.display = 'block';

        // Update amounts
        totalAmount.textContent = '₱' + total.toFixed(2);
        amountPaid.textContent = '₱' + paid.toFixed(2);
        outstandingBalance.textContent = '₱' + balance.toFixed(2);

        // Set max amount for payment input
        amountPaidInput.max = balance;
        amountPaidInput.placeholder = 'Max: ₱' + balance.toFixed(2);
    });

    // Set max amount when amount input changes
    amountPaidInput.addEventListener('input', function() {
        const selectedOption = orderSelect.options[orderSelect.selectedIndex];
        if (selectedOption.value !== '') {
            const balance = parseFloat(selectedOption.getAttribute('data-balance')) || 0;
            if (parseFloat(this.value) > balance) {
                this.setCustomValidity('Payment amount cannot exceed outstanding balance');
            } else {
                this.setCustomValidity('');
            }
        }
    });
});
</script>
@endsection
