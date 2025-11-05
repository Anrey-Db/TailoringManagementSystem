@extends('layouts.app')

@section('content')
<div class="container">
    <div class="header-section mb-4 text-center p-4 bg-white rounded shadow-sm">
        <h1 class="display-6 mb-2">
            <i class="fas fa-credit-card me-2"></i>New Payment
        </h1>
        <p class="text-muted mb-0">Record payment for customer orders</p>
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
            <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <!-- Customer & Order Selection -->
                    <div class="mb-4">
                        <h5 class="mb-3 text-muted">
                            <i class="fas fa-user me-2"></i>Customer & Order Information
                        </h5>
                        <div class="form-group">
                            <select name="order_id" id="order_id" class="form-select form-select-lg @error('order_id') is-invalid @enderror" required>
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
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div id="order-summary" class="text-center py-4">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">Select an order to view details</h6>
                    </div>

                    <div id="order-details" class="payment-details" style="display: none;">
                        <div class="info-section border-bottom pb-3 mb-4">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="text-muted mb-1">Total Amount</h6>
                                    <h4 id="total-amount" class="mb-0">₱0.00</h4>
                                </div>
                                <div class="col text-center">
                                    <h6 class="text-muted mb-1">Amount Paid</h6>
                                    <h4 id="amount-paid" class="mb-0 text-info">₱0.00</h4>
                                </div>
                                <div class="col text-end">
                                    <h6 class="text-muted mb-1">Outstanding Balance</h6>
                                    <h4 id="outstanding-balance" class="mb-0 text-danger">₱0.00</h4>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Form -->
                        <div class="payment-form">
                            <h5 class="mb-3 text-muted">
                                <i class="fas fa-credit-card me-2"></i>Payment Details
                            </h5>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" step="0.01" name="amount_paid" id="amount_paid" 
                                               class="form-control form-control-lg @error('amount_paid') is-invalid @enderror" 
                                               placeholder="0.00" required>
                                        <label for="amount_paid">Amount Paid <span class="text-danger">*</span></label>
                                        @error('amount_paid')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select name="payment_method" class="form-select form-select-lg @error('payment_method') is-invalid @enderror" required>
                                            <option value="">Select Method</option>
                                            <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="GCash" {{ old('payment_method') == 'GCash' ? 'selected' : '' }}>GCash</option>
                                        </select>
                                        <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                                        @error('payment_method')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" name="payment_date" 
                                               class="form-control form-control-lg @error('payment_date') is-invalid @enderror" 
                                               value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                        <label for="payment_date">Payment Date <span class="text-danger">*</span></label>
                                        @error('payment_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" name="reference_number" id="reference_number"
                                               class="form-control form-control-lg @error('reference_number') is-invalid @enderror" 
                                               placeholder="Transaction reference" 
                                               value="{{ old('reference_number') }}">
                                        <label for="reference_number">Reference Number</label>
                                        @error('reference_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Optional: Receipt number, transaction ID</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="fas fa-arrow-left me-2"></i>Back to Payments
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-4">
                                <i class="fas fa-receipt me-2"></i>Record Payment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.header-section {
    background: linear-gradient(to right, #f8f9fa, #ffffff);
    border-bottom: 3px solid #007bff;
}

.payment-details {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.info-section {
    margin-bottom: 1.5rem;
}

.form-floating > label {
    padding-left: 1rem;
}

.form-control-lg, .form-select-lg {
    height: 3.5rem;
    font-size: 1rem;
}

.form-floating > .form-control-lg,
.form-floating > .form-select-lg {
    padding-top: 1.625rem;
    padding-bottom: 0.625rem;
}

.invalid-feedback {
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

#order-summary i {
    opacity: 0.5;
}

.text-info {
    color: #0dcaf0 !important;
}

.text-danger {
    color: #dc3545 !important;
}

@media (max-width: 767.98px) {
    .col {
        margin-bottom: 1rem;
    }
}
</style>

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
