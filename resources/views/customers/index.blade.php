@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-users me-2"></i>Customer Management
                    </h1>
                    <p class="text-muted">Manage customer information and details</p>
                </div>
                <a href="{{ route('customers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add New Customer
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

    <!-- Search and Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('customers.index') }}" class="row g-3">
                <div class="col-md-6">
                    <label for="search" class="form-label">Search Customers</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search by name, contact, or email...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="sort_by" class="form-label">Sort By</label>
                    <select class="form-select" id="sort_by" name="sort_by">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Added</option>
                        <option value="first_name" {{ request('sort_by') == 'first_name' ? 'selected' : '' }}>First Name</option>
                        <option value="last_name" {{ request('sort_by') == 'last_name' ? 'selected' : '' }}>Last Name</option>
                        <option value="contact_number" {{ request('sort_by') == 'contact_number' ? 'selected' : '' }}>Contact Number</option>
                        <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="sort_order" class="form-label">Order</label>
                    <select class="form-select" id="sort_order" name="sort_order">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="fas fa-filter me-1"></i>Apply Filters
                    </button>
                    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Clear Filters
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Customers List
                <span class="badge bg-primary ms-2">{{ $customers->total() }}</span>
            </h5>
        </div>
        <div class="card-body">
            @if($customers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Contact Number</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Date Added</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            {{ strtoupper(substr($customer->first_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $customer->first_name }} {{ $customer->last_name }}</div>
                                            @if($customer->notes)
                                                <small class="text-muted">{{ Str::limit($customer->notes, 30) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($customer->contact_number)
                                        <a href="tel:{{ $customer->contact_number }}" class="text-decoration-none">
                                            <i class="fas fa-phone me-1"></i>{{ $customer->contact_number }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($customer->email)
                                        <a href="mailto:{{ $customer->email }}" class="text-decoration-none">
                                            <i class="fas fa-envelope me-1"></i>{{ $customer->email }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($customer->address)
                                        <span title="{{ $customer->address }}">
                                            {{ Str::limit($customer->address, 30) }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $customer->created_at->format('M d, Y') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('customers.show', $customer) }}" 
                                           class="btn btn-sm btn-outline-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer) }}" 
                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('customers.destroy', $customer) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this customer? This will also delete all associated measurements and orders.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} 
                        of {{ $customers->total() }} customers
                    </div>
                    <div>
                        {{ $customers->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No customers found</h5>
                    <p class="text-muted">
                        @if(request('search'))
                            No customers match your search criteria.
                        @else
                            Get started by adding your first customer.
                        @endif
                    </p>
                    <a href="{{ route('customers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Add First Customer
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
}
</style>
@endsection
