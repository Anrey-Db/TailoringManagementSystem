@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Edit Payment</h2>

    <form action="{{ route('payments.update', $payment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="order_id" class="form-label">Order ID</label>
            <input type="number" name="order_id" class="form-control" value="{{ $payment->order_id }}" required>
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" step="0.01" name="amount" class="form-control" value="{{ $payment->amount }}" required>
        </div>

        <div class="mb-3">
            <label for="payment_method" class="form-label">Payment Method</label>
            <select name="payment_method" class="form-select" required>
                <option value="cash" {{ $payment->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                <option value="gcash" {{ $payment->payment_method == 'gcash' ? 'selected' : '' }}>GCash</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="payment_date" class="form-label">Payment Date</label>
            <input type="date" name="payment_date" class="form-control" value="{{ $payment->payment_date }}" required>
        </div>

        <button type="submit" class="btn btn-success">Update Payment</button>
        <a href="{{ route('payments.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
