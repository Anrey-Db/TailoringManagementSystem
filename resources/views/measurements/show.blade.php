@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Measurement Details</h2>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Customer:</strong> 
                @if($measurement->customer)
                    {{ $measurement->customer->first_name }} {{ $measurement->customer->last_name }}
                @else
                    N/A
                @endif
            </p>
            <p><strong>Type:</strong> {{ $measurement->measurement_type }}</p>
            <p><strong>Date:</strong> {{ $measurement->created_at->format('M d, Y') }}</p>
            @if($measurement->remarks)
                <p><strong>Remarks:</strong> {{ $measurement->remarks }}</p>
            @endif
        </div>
    </div>

    @if($measurement->items->count())
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Items</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Price/Item</th>
                            <th>Total</th>
                            <th>Per-Person</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($measurement->items as $item)
                        <tr>
                            <td>{{ $item->item_type }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₱{{ number_format($item->price_per_item, 2) }}</td>
                            <td>₱{{ number_format($item->total_price, 2) }}</td>
                            <td>
                                @if($item->units->count())
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Size</th>
                                                <th>Chest</th>
                                                <th>Waist</th>
                                                <th>Hip</th>
                                                <th>Length</th>
                                                <th>Unit Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($item->units as $i => $u)
                                            <tr>
                                                <td>{{ $i+1 }}</td>
                                                <td>{{ $u->person_name }}</td>
                                                <td>{{ $u->size_label }}</td>
                                                <td>{{ $u->chest }}</td>
                                                <td>{{ $u->waist }}</td>
                                                <td>{{ $u->hip }}</td>
                                                <td>{{ $u->length }}</td>
                                                <td>₱{{ number_format($u->unit_price, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <a href="{{ route('measurements.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
