@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Order</h2>

    <form action="{{ route('orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Customer</label>
            <select name="customer_id" class="form-control" required>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ $customer->id == $order->customer_id ? 'selected' : '' }}>
                        {{ $customer->first_name }} {{ $customer->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3"><label>Order Date</label><input type="date" name="order_date" value="{{ $order->order_date }}" class="form-control"></div>
        <div class="mb-3"><label>Due Date</label><input type="date" name="due_date" value="{{ $order->due_date }}" class="form-control"></div>
        <div class="mb-3">
            <label>Measurement</label>
            <select name="measurement_id" id="measurement_id" class="form-control" required>
                @foreach($measurements as $m)
                    <option value="{{ $m->id }}" {{ $order->measurement_id == $m->id ? 'selected' : '' }}>#{{ $m->id }} - {{ $m->measurement_type }} ({{ $m->created_at->format('M d, Y') }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3"><label>Status</label>
            <select name="status" class="form-control">
                <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="In Progress" {{ $order->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                <option value="Completed" {{ $order->status == 'Completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        <hr>
        <h5>Items</h5>
        <div id="items_container">
            @foreach($order->orderItems as $idx => $item)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Item Type</label>
                            <select name="items[{{ $idx }}][item_type]" class="form-control item-type">
                                @php $t = $item->item_type; @endphp
                                <option value="Shirt" {{ $t=='Shirt'?'selected':'' }}>Shirt</option>
                                <option value="Jersey" {{ $t=='Jersey'?'selected':'' }}>Jersey</option>
                                <option value="Jersey Short" {{ $t=='Jersey Short'?'selected':'' }}>Jersey Short</option>
                                <option value="Coat Up" {{ $t=='Coat Up'?'selected':'' }}>Coat Up</option>
                                <option value="Coat Down" {{ $t=='Coat Down'?'selected':'' }}>Coat Down</option>
                                <option value="Uniform" {{ $t=='Uniform'?'selected':'' }}>Uniform</option>
                                <option value="PE Uniform Up" {{ $t=='PE Uniform Up'?'selected':'' }}>PE Uniform Up</option>
                                <option value="PE Uniform Down" {{ $t=='PE Uniform Down'?'selected':'' }}>PE Uniform Down</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Quantity</label>
                            <input type="number" min="1" value="{{ $item->quantity }}" name="items[{{ $idx }}][quantity]" class="form-control qty-input">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Price per Item</label>
                            <input type="number" step="0.01" value="{{ $item->price_per_item }}" name="items[{{ $idx }}][price_per_item]" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Measurement (optional)</label>
                            <input type="number" name="items[{{ $idx }}][measurement_id]" class="form-control" value="{{ $item->measurement_id }}" placeholder="Measurement ID">
                        </div>
                    </div>

                    <div class="mt-3 per-person" style="display:none;">
                        <div class="row g-2 mb-2 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label">How many people?</label>
                                <input type="number" min="1" value="{{ max(1, $item->units->count()) }}" class="form-control people-count">
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-outline-secondary gen-grid">Generate Grid</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered unit-table mb-0">
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
                                    @foreach($item->units as $ui => $u)
                                    <tr>
                                        <td>{{ $ui + 1 }}</td>
                                        <td><input type="text" name="items[{{ $idx }}][units][{{ $ui }}][person_name]" class="form-control" value="{{ $u->person_name }}"/></td>
                                        <td><input type="text" name="items[{{ $idx }}][units][{{ $ui }}][size_label]" class="form-control" value="{{ $u->size_label }}"/></td>
                                        <td><input type="number" step="0.01" name="items[{{ $idx }}][units][{{ $ui }}][chest]" class="form-control" value="{{ $u->chest }}"/></td>
                                        <td><input type="number" step="0.01" name="items[{{ $idx }}][units][{{ $ui }}][waist]" class="form-control" value="{{ $u->waist }}"/></td>
                                        <td><input type="number" step="0.01" name="items[{{ $idx }}][units][{{ $ui }}][hip]" class="form-control" value="{{ $u->hip }}"/></td>
                                        <td><input type="number" step="0.01" name="items[{{ $idx }}][units][{{ $ui }}][length]" class="form-control" value="{{ $u->length }}"/></td>
                                        <td><input type="number" step="0.01" name="items[{{ $idx }}][units][{{ $ui }}][unit_price]" class="form-control" value="{{ $u->unit_price }}"/></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Update Order</button>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.card').forEach(card => {
        const typeSelect = card.querySelector('.item-type');
        const perPerson = card.querySelector('.per-person');
        const qtyInput = card.querySelector('.qty-input');
        const tbody = card.querySelector('.unit-table tbody');
        const genBtn = card.querySelector('.gen-grid');
        const peopleCountInput = card.querySelector('.people-count');
        if (!typeSelect || !perPerson) return;

        function togglePerPerson() {
            perPerson.style.display = (typeSelect.value && typeSelect.value.includes('Jersey')) ? '' : 'none';
        }
        function rebuildRows() {
            const count = parseInt(peopleCountInput.value || '0', 10);
            tbody.innerHTML = '';
            const idx = Array.prototype.indexOf.call(document.getElementById('items_container').children, card);
            for (let i = 0; i < count; i++) {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${i+1}</td>
                    <td><input type="text" name="items[${idx}][units][${i}][person_name]" class="form-control"/></td>
                    <td><input type="text" name="items[${idx}][units][${i}][size_label]" class="form-control"/></td>
                    <td><input type="number" step="0.01" name="items[${idx}][units][${i}][chest]" class="form-control"/></td>
                    <td><input type="number" step="0.01" name="items[${idx}][units][${i}][waist]" class="form-control"/></td>
                    <td><input type="number" step="0.01" name="items[${idx}][units][${i}][hip]" class="form-control"/></td>
                    <td><input type="number" step="0.01" name="items[${idx}][units][${i}][length]" class="form-control"/></td>
                    <td><input type="number" step="0.01" name="items[${idx}][units][${i}][unit_price]" class="form-control"/></td>
                `;
                tbody.appendChild(tr);
            }
            if (typeSelect.value && typeSelect.value.includes('Jersey')) {
                qtyInput.value = count || 1;
            }
        }
        typeSelect.addEventListener('change', togglePerPerson);
        if (genBtn) genBtn.addEventListener('click', rebuildRows);
        togglePerPerson();
    });
});
</script>
@endsection
