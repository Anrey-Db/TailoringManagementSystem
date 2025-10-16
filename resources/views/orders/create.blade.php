@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Create New Order
                    </h1>
                    <p class="text-muted">Create new orders for customers</p>
                </div>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Orders
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

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        
        <div class="row">
            <!-- Order Details -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Order Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                            <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->first_name }} {{ $customer->last_name }}
                                        @if($customer->contact_number) - {{ $customer->contact_number }} @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="measurement_id" class="form-label">Measurement Reference</label>
                            <select class="form-select @error('measurement_id') is-invalid @enderror" id="measurement_id" name="measurement_id">
                                <option value="">No Measurement Reference</option>
                                @foreach($measurements as $measurement)
                                    <option value="{{ $measurement->id }}" 
                                            data-customer="{{ $measurement->customer_id }}"
                                            data-total="{{ $measurement->items->sum('total_price') }}"
                                            {{ old('measurement_id') == $measurement->id ? 'selected' : '' }}>
                                        #{{ $measurement->id }} - {{ $measurement->measurement_type }}
                                        ({{ $measurement->customer->first_name }} {{ $measurement->customer->last_name }})
                                        - ₱{{ number_format($measurement->items->sum('total_price'), 2) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('measurement_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Select a measurement to automatically calculate order cost</div>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Order Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="In Progress" {{ old('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Delivered" {{ old('status') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                   id="due_date" name="due_date" value="{{ old('due_date') }}" min="{{ date('Y-m-d') }}">
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea class="form-control @error('remarks') is-invalid @enderror" 
                                      id="remarks" name="remarks" rows="3" 
                                      placeholder="Additional notes for this order...">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Cost Preview -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-calculator me-2"></i>Order Cost Preview
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="cost-preview">
                            <div class="text-center py-4">
                                <i class="fas fa-info-circle fa-2x text-muted mb-3"></i>
                                <h6 class="text-muted">Select a measurement to see order cost</h6>
                                <p class="text-muted small">The total amount will be calculated automatically from the selected measurement</p>
                            </div>
                        </div>

                        <div id="measurement-details" style="display: none;">
                            <h6>Measurement Items:</h6>
                            <div id="items-list" class="mb-3"></div>
                            
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label fw-bold">Total Amount:</label>
                                </div>
                                <div class="col-6 text-end">
                                    <span id="total-amount" class="h5 text-success">₱0.00</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label fw-bold">Amount Paid:</label>
                                </div>
                                <div class="col-6 text-end">
                                    <span id="amount-paid" class="h6 text-info">₱0.00</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label fw-bold">Balance:</label>
                                </div>
                                <div class="col-6 text-end">
                                    <span id="balance" class="h6 text-danger">₱0.00</span>
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
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Create Order
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
    const customerSelect = document.getElementById('customer_id');
    const measurementSelect = document.getElementById('measurement_id');
    const costPreview = document.getElementById('cost-preview');
    const measurementDetails = document.getElementById('measurement-details');
    const itemsList = document.getElementById('items-list');
    const totalAmount = document.getElementById('total-amount');
    const amountPaid = document.getElementById('amount-paid');
    const balance = document.getElementById('balance');

    // Filter measurements based on selected customer
    customerSelect.addEventListener('change', function() {
        const selectedCustomerId = this.value;
        const options = measurementSelect.querySelectorAll('option');
        
        options.forEach(option => {
            if (option.value === '') {
                option.style.display = 'block';
                return;
            }
            
            const customerId = option.getAttribute('data-customer');
            if (selectedCustomerId === '' || customerId === selectedCustomerId) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
        
        // Reset measurement selection
        measurementSelect.value = '';
        showCostPreview();
    });

    // Show cost preview when measurement is selected
    measurementSelect.addEventListener('change', function() {
        showCostPreview();
    });

    function showCostPreview() {
        const selectedOption = measurementSelect.options[measurementSelect.selectedIndex];
        
        if (selectedOption.value === '') {
            costPreview.style.display = 'block';
            measurementDetails.style.display = 'none';
            return;
        }

        const total = parseFloat(selectedOption.getAttribute('data-total')) || 0;
        
        // Show measurement details
        costPreview.style.display = 'none';
        measurementDetails.style.display = 'block';
        
        // Update amounts
        totalAmount.textContent = '₱' + total.toFixed(2);
        amountPaid.textContent = '₱0.00';
        balance.textContent = '₱' + total.toFixed(2);
        
        // Show items (simplified for now)
        itemsList.innerHTML = '<div class="text-muted">Items will be loaded from measurement</div>';
    }
});
</script>
@endsection