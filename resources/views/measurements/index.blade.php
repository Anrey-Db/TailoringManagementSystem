@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-ruler me-2"></i>Measurement Management
                    </h1>
                    <p class="text-muted">Manage customer body measurements for accurate tailoring</p>
                </div>
                <a href="{{ route('measurements.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Add New Measurement
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
            <form method="GET" action="{{ route('measurements.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search by Customer</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search by customer name...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="measurement_type" class="form-label">Measurement Type</label>
                    <select class="form-select" id="measurement_type" name="measurement_type">
                        <option value="">All Types</option>
                        @foreach($measurementTypes as $type)
                            <option value="{{ $type }}" {{ request('measurement_type') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="sort_by" class="form-label">Sort By</label>
                    <select class="form-select" id="sort_by" name="sort_by">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Added</option>
                        <option value="measurement_type" {{ request('sort_by') == 'measurement_type' ? 'selected' : '' }}>Type</option>
                    </select>
                </div>
                <div class="col-md-2">
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
                    <a href="{{ route('measurements.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Clear Filters
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Measurements Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Measurements List
                <span class="badge bg-primary ms-2">{{ $measurements->total() }}</span>
            </h5>
        </div>
        <div class="card-body">
            @if($measurements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Measurement Type</th>
                                <th>Key Measurements</th>
                                <th>Date Measured</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($measurements as $measurement)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            {{ strtoupper(substr($measurement->customer->first_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">
                                                <a href="{{ route('customers.show', $measurement->customer) }}" class="text-decoration-none">
                                                    {{ $measurement->customer->first_name }} {{ $measurement->customer->last_name }}
                                                </a>
                                            </div>
                                            @if($measurement->customer->contact_number)
                                                <small class="text-muted">{{ $measurement->customer->contact_number }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $measurement->measurement_type }}</span>
                                </td>
                                <td>
                                    @php
                                        $keyMeasurements = [];
                                        // Get the first item and its units
                                        $firstItem = $measurement->items->first();
                                        if($firstItem && $firstItem->units->count() > 0) {
                                            $firstUnit = $firstItem->units->first();
                                            
                                            // Add person name if available
                                            if($firstUnit->person_name) {
                                                $keyMeasurements[] = 'Person: ' . $firstUnit->person_name;
                                            }
                                            
                                            // Get the size label if available
                                            if($firstUnit->size_label) {
                                                $keyMeasurements[] = 'Size: ' . $firstUnit->size_label;
                                            }
                                            
                                            // Add the essential measurements
                                            if($firstUnit->chest) {
                                                $keyMeasurements[] = 'Chest: ' . $firstUnit->chest;
                                            }
                                            if($firstUnit->waist) {
                                                $keyMeasurements[] = 'Waist: ' . $firstUnit->waist;
                                            }
                                            if($firstUnit->hip) {
                                                $keyMeasurements[] = 'Hip: ' . $firstUnit->hip;
                                            }
                                            if($firstUnit->length) {
                                                $keyMeasurements[] = 'Length: ' . $firstUnit->length;
                                            }
                                            
                                            // Show count if there are multiple units
                                            if($firstItem->units->count() > 1) {
                                                $keyMeasurements[] = '+'.($firstItem->units->count() - 1).' more persons';
                                            }
                                        }
                                    @endphp
                                    @if(count($keyMeasurements) > 0)
                                        <div class="small">
                                            @foreach($keyMeasurements as $measure)
                                                <div>{{ $measure }}</div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted">No measurements recorded</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $measurement->created_at->format('M d, Y') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('measurements.show', $measurement) }}" 
                                           class="btn btn-sm btn-outline-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('measurements.edit', $measurement) }}" 
                                           class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('measurements.destroy', $measurement) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this measurement?')">
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
                        Showing {{ $measurements->firstItem() }} to {{ $measurements->lastItem() }} 
                        of {{ $measurements->total() }} measurements
                    </div>
                    <div>
                        {{ $measurements->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-ruler fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No measurements found</h5>
                    <p class="text-muted">
                        @if(request('search') || request('measurement_type'))
                            No measurements match your search criteria.
                        @else
                            Get started by adding your first measurement.
                        @endif
                    </p>
                    <a href="{{ route('measurements.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Add First Measurement
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
