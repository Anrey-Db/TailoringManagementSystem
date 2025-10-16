@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Measurement</h2>

    <form action="{{ route('measurements.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Customer Name</label>
            <select name="customer_id" class="form-control" required>
                <option value="">Select Customer</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ (isset($selectedCustomerId) && (int)$selectedCustomerId === (int)$customer->id) ? 'selected' : '' }}>
                        {{ $customer->first_name }} {{ $customer->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        

        <hr>
        <h5>Items</h5>
        <div id="m_items_container"></div>
        <button type="button" class="btn btn-outline-primary mb-3" id="m_add_item_btn">+ Add Item</button>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('measurements.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script>
// Removed legacy measurement-type sections for a cleaner measurement-first flow

// Items grid under measurement
let mItemIndex = 0;
const mItemsContainer = document.getElementById('m_items_container');
document.getElementById('m_add_item_btn').addEventListener('click', () => addMItem());

function addMItem() {
    const idx = mItemIndex++;
    const wrapper = document.createElement('div');
    wrapper.className = 'card mb-3';
    wrapper.innerHTML = `
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Item Type</label>
                    <select name="items[${idx}][item_type]" class="form-control item-type">
                        <option value="Shirt">Shirt</option>
                        <option value="Uniform">Uniform</option>
                        <option value="PE Uniform">PE Uniform</option>
                        <option value="Jersey">Jersey</option>
                        <option value="Coat">Coat</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Quantity</label>
                    <input type="number" min="1" value="1" name="items[${idx}][quantity]" class="form-control qty-input">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Price per Item</label>
                    <input type="number" step="0.01" value="0" name="items[${idx}][price_per_item]" class="form-control">
                </div>
                <div class="col-md-5 text-end">
                    <button type="button" class="btn btn-outline-danger" onclick="this.closest('.card').remove()">Remove</button>
                </div>
            </div>

            <div class="mt-3 per-person" style="display:none;">
                <div class="row g-2 mb-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">How many people?</label>
                        <input type="number" min="1" value="1" class="form-control people-count">
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
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
    mItemsContainer.appendChild(wrapper);

    const typeSelect = wrapper.querySelector('.item-type');
    const perPerson = wrapper.querySelector('.per-person');
    const qtyInput = wrapper.querySelector('.qty-input');
    const tbody = wrapper.querySelector('.unit-table tbody');
    const genBtn = wrapper.querySelector('.gen-grid');
    const peopleCountInput = wrapper.querySelector('.people-count');

    function togglePerPerson() {
        // Show per-person grid for all item types
        perPerson.style.display = '';
    }
    function rebuildRows() {
        const count = parseInt(peopleCountInput.value || '0', 10);
        tbody.innerHTML = '';
        for (let i = 0; i < count; i++) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${i+1}</td>
                <td><input type="text" name="items[${idx}][units][${i}][person_name]" class="form-control"/></td>
                <td>
                    <select name="items[${idx}][units][${i}][size_label]" class="form-control unit-size"></select>
                </td>
                <td><input type="number" step="0.01" name="items[${idx}][units][${i}][chest]" class="form-control unit-chest"/></td>
                <td><input type="number" step="0.01" name="items[${idx}][units][${i}][waist]" class="form-control unit-waist"/></td>
                <td><input type="number" step="0.01" name="items[${idx}][units][${i}][hip]" class="form-control unit-hip"/></td>
                <td><input type="number" step="0.01" name="items[${idx}][units][${i}][length]" class="form-control unit-length"/></td>
                <td><input type="number" step="0.01" name="items[${idx}][units][${i}][shoulder]" class="form-control unit-shoulder"/></td>
                <td><input type="number" step="0.01" name="items[${idx}][units][${i}][sleeve]" class="form-control unit-sleeve"/></td>
                <td><input type="number" step="0.01" name="items[${idx}][units][${i}][inseam]" class="form-control unit-inseam"/></td>
                <td><input type="number" step="0.01" name="items[${idx}][units][${i}][outseam]" class="form-control unit-outseam"/></td>
                <td><input type="number" step="0.01" name="items[${idx}][units][${i}][thigh]" class="form-control unit-thigh"/></td>
                <td><input type="number" step="0.01" name="items[${idx}][units][${i}][leg_opening]" class="form-control unit-leg_opening"/></td>
                <td><input type="number" step="0.01" name="items[${idx}][units][${i}][unit_price]" class="form-control"/></td>
            `;
            tbody.appendChild(tr);
        }
        // Sync quantity to the number of people for all item types
        qtyInput.value = count || 1;

        // Apply size chart placeholders and size options after building rows
        applySizeChartPlaceholders();
        applySizeOptions();
    }
    typeSelect.addEventListener('change', () => {
        togglePerPerson();
        applySizeChartPlaceholders();
        applySizeOptions();
    });
    genBtn.addEventListener('click', rebuildRows);
    togglePerPerson();

    // Size chart mapping and placeholder applier
    // Full size chart (cm) — Philippine School Uniforms
    const sizeChart = {
        // Shirt / Blouse
        "Shirt": {
            chest: "81–116", // overall min/max across sizes
            shoulder: "36–50",
            sleeve: "21–26",
            length: "60–74",
            waist: "70–97",
            hip: "-",
            inseam: "-",
            outseam: "-",
            thigh: "-",
            leg_opening: "-",
        },
        // Uniform (Top & Bottom Combined) — map Top Length to length, Pants length to outseam
        "Uniform": {
            chest: "81–110",
            waist: "64–94",
            hip: "86–115",
            length: "56–66", // Top Length
            shoulder: "-",
            sleeve: "-",
            inseam: "-",
            outseam: "90–100", // Pants length (90–100) from 90/100 range
            thigh: "-",
            leg_opening: "-",
        },
        // PE Uniform (Shirt & Shorts/Jogging Pants) — map Shirt Length to length, Shorts/Pants to outseam
        "PE Uniform": {
            chest: "84–116",
            waist: "66–96",
            hip: "88–118",
            length: "64–74", // Shirt Length
            shoulder: "-",
            sleeve: "-",
            inseam: "-",
            outseam: "45–105", // Shorts 45–55 / Pants 95–105 consolidated
            thigh: "-",
            leg_opening: "-",
        },
        // Jersey (Top & Short) — map Jersey Length to length, Short Length to outseam
        "Jersey": {
            chest: "84–116",
            waist: "66–96",
            hip: "88–118",
            length: "68–78", // Jersey length
            shoulder: "-",
            sleeve: "-",
            inseam: "-",
            outseam: "45–55", // short length
            thigh: "-",
            leg_opening: "-",
        },
        // Coat / Blazer — map Coat Length to length
        "Coat": {
            chest: "86–116",
            shoulder: "40–50",
            sleeve: "56–61",
            waist: "74–103",
            length: "65–75",
            hip: "-",
            inseam: "-",
            outseam: "-",
            thigh: "-",
            leg_opening: "-",
        },
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
            const shoulder = tr.querySelector('.unit-shoulder');
            const sleeve = tr.querySelector('.unit-sleeve');
            const inseam = tr.querySelector('.unit-inseam');
            const outseam = tr.querySelector('.unit-outseam');
            const thigh = tr.querySelector('.unit-thigh');
            const leg = tr.querySelector('.unit-leg_opening');
            if (shoulder) shoulder.placeholder = chart.shoulder;
            if (sleeve) sleeve.placeholder = chart.sleeve;
            if (inseam) inseam.placeholder = chart.inseam;
            if (outseam) outseam.placeholder = chart.outseam;
            if (thigh) thigh.placeholder = chart.thigh;
            if (leg) leg.placeholder = chart.leg_opening;
        });
    }

    // Full size sets per item type
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
            // Keep custom value if it existed and not in list
            if (current && !options.includes(current)) {
                const custom = document.createElement('option');
                custom.value = current;
                custom.textContent = current;
                sel.appendChild(custom);
            }
            sel.value = current;
        });
    }
}
</script>
<style>
/* Ensure the per-person grid doesn't get cut and can scroll horizontally */
.unit-table { min-width: 1400px; }
.unit-table th, .unit-table td { white-space: nowrap; vertical-align: middle; }
.unit-table input.form-control, .unit-table select.form-control { min-width: 90px; }
</style>
</script>
@endsection
