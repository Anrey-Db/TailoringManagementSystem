@extends('layouts.app')

@section('content')
<h2 class="mb-4">Edit Customer</h2>

<form action="{{ route('customers.update', $customer->id) }}" method="POST" class="mt-3">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" id="first_name" name="first_name" value="{{ $customer->first_name }}" class="form-control" required>
        </div>

        <div class="col-md-6 mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="{{ $customer->last_name }}" class="form-control" required>
        </div>
    </div>

    <div class="mb-3">
        <label for="contact_number" class="form-label">Contact Number</label>
        <input type="text" id="contact_number" name="contact_number" value="{{ $customer->contact_number }}" class="form-control">
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" value="{{ $customer->email }}" class="form-control">
    </div>

    <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <textarea id="address" name="address" class="form-control" rows="2">{{ $customer->address }}</textarea>
    </div>
    <div class="mb-3">
        <label for="notes" class="form-label">Notes</label>
        <textarea id="notes" name="notes" class="form-control" rows="3">{{ $customer->notes }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('customers.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
