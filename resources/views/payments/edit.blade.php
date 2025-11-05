@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Edit Payment</h2>

    <form action="{{ route('payments.update', $payment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="order_id" class="form-label">Order</label>
            <select name="order_id" class="form-select" required>
                @foreach($orders as $order)
                    <option value="{{ $order->id }}" {{ $payment->order_id == $order->id ? 'selected' : '' }}>
                        {{ $order->order_number }} - {{ $order->customer->first_name }} {{ $order->customer->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="amount_paid" class="form-label">Amount Paid</label>
            <input type="number" step="0.01" name="amount_paid" class="form-control" value="{{ old('amount_paid', $payment->amount_paid) }}" required>
        </div>

        <div class="mb-3">
            <label for="payment_method" class="form-label">Payment Method</label>
            <select name="payment_method" class="form-select" required>
                <option value="">Select Method</option>
                <option value="Cash" {{ $payment->payment_method == 'Cash' ? 'selected' : '' }}>Cash</option>
                <option value="GCash" {{ $payment->payment_method == 'GCash' ? 'selected' : '' }}>GCash</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="payment_date" class="form-label">Payment Date</label>
            <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', optional($payment->payment_date)->format('Y-m-d')) }}" required>
        </div>

        <div class="mb-3">
            <label for="reference_number" class="form-label">Reference Number</label>
            <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number', $payment->reference_number) }}">
        </div>

        <button type="submit" class="btn btn-success">Update Payment</button>
        <a href="{{ route('payments.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
