@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Measurement</h2>

    <form action="{{ route('measurements.update', $measurement->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Customer Name</label>
            <select name="customer_id" class="form-control">
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ $customer->id == $measurement->customer_id ? 'selected' : '' }}>
                        {{ $customer->first_name }} {{ $customer->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        

        <hr>
        <h5>Items</h5>
        <div id="m_items_container">
            @foreach($measurement->items as $idx => $item)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Item Type</label>
                            <select name="items[{{ $idx }}][item_type]" class="form-control item-type">
                                @php $t = $item->item_type; @endphp
                                <option value="Shirt" {{ $t=='Shirt'?'selected':'' }}>Shirt</option>
                                <option value="Uniform" {{ $t=='Uniform'?'selected':'' }}>Uniform</option>
                                <option value="PE Uniform" {{ $t=='PE Uniform'?'selected':'' }}>PE Uniform</option>
                                <option value="Jersey" {{ $t=='Jersey'?'selected':'' }}>Jersey</option>
                                <option value="Coat" {{ $t=='Coat'?'selected':'' }}>Coat</option>
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
                                        <th>Shoulder</th>
                                        <th>Sleeve</th>
                                        <th>Inseam</th>
                                        <th>Outseam</th>
                                        <th>Thigh</th>
                                        <th>Leg Opening</th>
                                        <th>Unit Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($item->units as $ui => $u)
                                    <tr>
                                        <td>{{ $ui + 1 }}</td>
                                        <td><input type="text" name="items[{{ $idx }}][units][{{ $ui }}][person_name]" class="form-control" value="{{ $u->person_name }}"/></td>
                                        <td>
                                            <select name="items[{{ $idx }}][units][{{ $ui }}][size_label]" class="form-control unit-size">
                                                <option value="{{ $u->size_label }}" selected>{{ $u->size_label }}</option>
                                            </select>
                                        </td>
                                        <td><input type="number" step="0.01" name="items[{{ $idx }}][units][{{ $ui }}][chest]" class="form-control unit-chest" value="{{ $u->chest }}"/></td>
                                        <td><input type="number" step="0.01" name="items[{{ $idx }}][units][{{ $ui }}][waist]" class="form-control unit-waist" value="{{ $u->waist }}"/></td>
                                        <td><input type="number" step="0.01" name="items[{{ $idx }}][units][{{ $ui }}][hip]" class="form-control unit-hip" value="{{ $u->hip }}"/></td>
                                        <td><input type="number" step="0.01" name="items[{{ $idx }}][units][{{ $ui }}][length]" class="form-control unit-length" value="{{ $u->length }}"/></td>
                                        <td><input type="number" step="0.01" name="items[{{ $idx }}][units][{{ $ui }}][shoulder]" class="form-control unit-shoulder" value="{{ $u->shoulder }}"/></td>
                                        <td><input type="number" step="0.01" name="items[{{ $idx }}][units][{{ $ui }}][sleeve]" class="form-control unit-sleeve" value="{{ $u->sleeve }}"/></td>
                                        <td><input type="number" step="0.01" name="items[{{ $idx }}][units][{{ $ui }}][inseam]" class="form-control unit-inseam" value="{{ $u->inseam }}"/></td>
                                        <td><input type="number" step="0.01" name="items[{{ $idx }}][units][{{ $ui }}][outseam]" class="form-control unit-outseam" value="{{ $u->outseam }}"/></td>
                                        <td><input type="number" step="0.01" name="items[{{ $idx }}][units][{{ $ui }}][thigh]" class="form-control unit-thigh" value="{{ $u->thigh }}"/></td>
                                        <td><input type="number" step="0.01" name="items[{{ $idx }}][units][{{ $ui }}][leg_opening]" class="form-control unit-leg_opening" value="{{ $u->leg_opening }}"/></td>
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
        <button type="button" class="btn btn-outline-primary mb-3" id="m_add_item_btn">+ Add Item</button>

        <div class="mb-3"><label>Chest</label><input type="number" step="0.01" name="chest" class="form-control" value="{{ $measurement->chest }}"></div>
        <div class="mb-3"><label>Waist</label><input type="number" step="0.01" name="waist" class="form-control" value="{{ $measurement->waist }}"></div>
        <div class="mb-3"><label>Hip</label><input type="number" step="0.01" name="hip" class="form-control" value="{{ $measurement->hip }}"></div>
        <div class="mb-3"><label>Length</label><input type="number" step="0.01" name="length" class="form-control" value="{{ $measurement->length }}"></div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('measurements.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script>
// toggle per-person and generate grid (similar to create)
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#m_items_container .card').forEach(wrapper => {
        const typeSelect = wrapper.querySelector('.item-type');
        const perPerson = wrapper.querySelector('.per-person');
        const qtyInput = wrapper.querySelector('.qty-input');
        const tbody = wrapper.querySelector('.unit-table tbody');
        const genBtn = wrapper.querySelector('.gen-grid');
        const peopleCountInput = wrapper.querySelector('.people-count');
        function togglePerPerson() { perPerson.style.display = ''; }
        function rebuildRows() {
            const count = parseInt(peopleCountInput.value || '0', 10);
            tbody.innerHTML = '';
            const idx = Array.prototype.indexOf.call(document.getElementById('m_items_container').children, wrapper);
            for (let i = 0; i < count; i++) {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${i+1}</td>
                    <td><input type="text" name="items[${idx}][units][${i}][person_name]" class="form-control"/></td>
                    <td><select name="items[${idx}][units][${i}][size_label]" class="form-control unit-size"></select></td>
                    <td><input type="number" step="0.01" name="items[${idx}][units][${i}][chest]" class="form-control unit-chest"/></td>
                    <td><input type="number" step="0.01" name="items[${idx}][units][${i}][waist]" class="form-control unit-waist"/></td>
                    <td><input type="number" step="0.01" name="items[${idx}][units][${i}][hip]" class="form-control unit-hip"/></td>
                    <td><input type="number" step="0.01" name="items[${idx}][units][${i}][length]" class="form-control unit-length"/></td>
                    <td><input type="number" step="0.01" name="items[${idx}][units][${i}][unit_price]" class="form-control"/></td>
                `;
                tbody.appendChild(tr);
            }
            qtyInput.value = count || 1;
            applySizeChartPlaceholders();
            applySizeOptions();
        }
        typeSelect.addEventListener('change', () => { togglePerPerson(); applySizeChartPlaceholders(); applySizeOptions(); });
        if (genBtn) genBtn.addEventListener('click', rebuildRows);
        togglePerPerson();

        // Full size chart (cm) — Philippine School Uniforms
        const sizeChart = {
            "Shirt": { chest: "81–116", shoulder:"36–50", sleeve:"21–26", length:"60–74", waist:"70–97", hip:"-", inseam:"-", outseam:"-", thigh:"-", leg_opening:"-" },
            "Uniform": { chest: "81–110", waist:"64–94", hip:"86–115", length:"56–66", shoulder:"-", sleeve:"-", inseam:"-", outseam:"90–100", thigh:"-", leg_opening:"-" },
            "PE Uniform": { chest: "84–116", waist:"66–96", hip:"88–118", length:"64–74", shoulder:"-", sleeve:"-", inseam:"-", outseam:"45–105", thigh:"-", leg_opening:"-" },
            "Jersey": { chest: "84–116", waist:"66–96", hip:"88–118", length:"68–78", shoulder:"-", sleeve:"-", inseam:"-", outseam:"45–55", thigh:"-", leg_opening:"-" },
            "Coat": { chest: "86–116", shoulder:"40–50", sleeve:"56–61", waist:"74–103", length:"65–75", hip:"-", inseam:"-", outseam:"-", thigh:"-", leg_opening:"-" },
        };
        function applySizeChartPlaceholders() {
            const itemType = typeSelect.value;
            const chart = sizeChart[itemType];
            if (!chart) return;
            tbody.querySelectorAll('tr').forEach(tr => {
                const chest = tr.querySelector('.unit-chest');
                const waist = tr.querySelector('.unit-waist');
                const hip = tr.querySelector('.unit-hip');
                const length = tr.querySelector('.unit-length');
                if (chest) chest.placeholder = chart.chest;
                if (waist) waist.placeholder = chart.waist;
                if (hip) hip.placeholder = chart.hip;
                if (length) length.placeholder = chart.length;
            });
        }

        const sizeOptionsMap = {
            "Shirt": ["XS","S","M","L","XL","2XL","3XL"],
            "Uniform": ["XS","S","M","L","XL","2XL","3XL"],
            "PE Uniform": ["XS","S","M","L","XL","2XL","3XL"],
            "Jersey": ["XS","S","M","L","XL","2XL"],
            "Coat": ["34","36","38","40","42","44","46"],
        };
        function applySizeOptions() {
            const itemType = typeSelect.value;
            const options = sizeOptionsMap[itemType] || [];
            tbody.querySelectorAll('tr').forEach(tr => {
                const sel = tr.querySelector('.unit-size');
                if (!sel) return;
                const current = sel.value || '';
                sel.innerHTML = '';
                const placeholderOpt = document.createElement('option');
                placeholderOpt.value = '';
                placeholderOpt.textContent = 'Select size';
                sel.appendChild(placeholderOpt);
                options.forEach(v => {
                    const opt = document.createElement('option');
                    opt.value = v;
                    opt.textContent = v;
                    sel.appendChild(opt);
                });
                if (current && !options.includes(current)) {
                    const custom = document.createElement('option');
                    custom.value = current;
                    custom.textContent = current;
                    sel.appendChild(custom);
                }
                sel.value = current;
            });
        }
        applySizeOptions();
    });
    // add button support
    document.getElementById('m_add_item_btn').addEventListener('click', () => {
        // reuse function from create by cloning addMItem if desired
        // For brevity, we can trigger a soft reload or instruct adding via create page pattern
    });
});
</script>
<script>
// Removed legacy measurement-type sections for a cleaner measurement-first flow
</script>
<style>
/* Ensure the per-person grid doesn't get cut and can scroll horizontally */
.unit-table { min-width: 1400px; }
.unit-table th, .unit-table td { white-space: nowrap; vertical-align: middle; }
.unit-table input.form-control, .unit-table select.form-control { min-width: 90px; }
</style>
@endsection
