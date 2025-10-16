<?php

namespace App\Http\Controllers;

use App\Models\Measurement;
use App\Models\Customer;
use App\Models\MeasurementItem;
use App\Models\MeasurementItemUnit;
use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    /**
     * Display a listing of measurements.
     */
    public function index(Request $request)
    {
        $query = Measurement::with('customer');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('customer', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Filter by measurement type
        if ($request->filled('measurement_type')) {
            $query->where('measurement_type', $request->get('measurement_type'));
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['measurement_type', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        $measurements = $query->paginate(10)->withQueryString();
        
        // Get measurement types for filter dropdown
        $measurementTypes = Measurement::distinct()->pluck('measurement_type')->sort();
        
        return view('measurements.index', compact('measurements', 'measurementTypes'));
    }

    /**
     * Show the form for creating a new measurement.
     */
    public function create(Request $request)
    {
        $customers = Customer::orderBy('first_name')->get();
        $selectedCustomerId = $request->get('customer_id');
        return view('measurements.create', compact('customers', 'selectedCustomerId'));
    }

    /**
     * Store a newly created measurement in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'remarks' => 'nullable|string',
        ]);

        // Add dynamic measurement fields depending on type
        $fields = $request->except(['_token']);
        $items = $fields['items'] ?? [];
        unset($fields['items']);
        // Auto-derive measurement_type from first item type to satisfy DB enum
        if (empty($fields['measurement_type'])) {
            $fields['measurement_type'] = $items[0]['item_type'] ?? 'Shirt';
        }
        
        $measurement = null;
        \DB::transaction(function () use (&$measurement, $fields, $items) {
            $measurement = Measurement::create($fields);
            foreach ($items as $item) {
                $mi = MeasurementItem::create([
                    'measurement_id' => $measurement->id,
                    'item_type' => $item['item_type'],
                    'quantity' => $item['quantity'] ?? 1,
                    'price_per_item' => $item['price_per_item'] ?? 0,
                    'total_price' => 0,
                    'description' => $item['description'] ?? null,
                    'notes' => $item['notes'] ?? null,
                ]);
                $unitTotal = 0;
                if (!empty($item['units']) && is_array($item['units'])) {
                    $count = 0;
                    foreach ($item['units'] as $u) {
                        MeasurementItemUnit::create([
                            'measurement_item_id' => $mi->id,
                            'person_name' => $u['person_name'] ?? null,
                            'size_label' => $u['size_label'] ?? null,
                            'chest' => $u['chest'] ?? null,
                            'waist' => $u['waist'] ?? null,
                            'hip' => $u['hip'] ?? null,
                            'length' => $u['length'] ?? null,
                            'unit_price' => $u['unit_price'] ?? null,
                        ]);
                        $count++;
                        if (isset($u['unit_price']) && $u['unit_price'] !== '') {
                            $unitTotal += (float) $u['unit_price'];
                        }
                    }
                    if (str_contains($mi->item_type, 'Jersey')) {
                        $mi->quantity = max(1, $count);
                    }
                }
                $mi->total_price = $unitTotal > 0 ? $unitTotal : ($mi->quantity * (float) $mi->price_per_item);
                $mi->saveQuietly();
            }
        });

        return redirect()->route('measurements.index')
                         ->with('success', 'Measurement added successfully!');
    }

    /**
     * Display the specified measurement.
     */
    public function show(string $id)
    {
        $measurement = Measurement::with(['customer','items.units'])->findOrFail($id);
        return view('measurements.show', compact('measurement'));
    }

    /**
     * Show the form for editing the specified measurement.
     */
    public function edit(string $id)
    {
        $measurement = Measurement::findOrFail($id);
        $customers = Customer::all();
        return view('measurements.edit', compact('measurement', 'customers'));
    }

    /**
     * Update the specified measurement in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'remarks' => 'nullable|string',
        ]);

        $measurement = Measurement::with('items.units')->findOrFail($id);
        $fields = $request->all();
        $items = $fields['items'] ?? [];
        unset($fields['items']);
        if (empty($fields['measurement_type'])) {
            $fields['measurement_type'] = $items[0]['item_type'] ?? $measurement->measurement_type ?? 'Shirt';
        }
        \DB::transaction(function () use ($measurement, $fields, $items) {
            $measurement->update($fields);
            // reset items
            foreach ($measurement->items as $mi) { $mi->units()->delete(); }
            $measurement->items()->delete();
            foreach ($items as $item) {
                $mi = MeasurementItem::create([
                    'measurement_id' => $measurement->id,
                    'item_type' => $item['item_type'],
                    'quantity' => $item['quantity'] ?? 1,
                    'price_per_item' => $item['price_per_item'] ?? 0,
                    'total_price' => 0,
                    'description' => $item['description'] ?? null,
                    'notes' => $item['notes'] ?? null,
                ]);
                $unitTotal = 0; $count = 0;
                if (!empty($item['units']) && is_array($item['units'])) {
                    foreach ($item['units'] as $u) {
                        MeasurementItemUnit::create([
                            'measurement_item_id' => $mi->id,
                            'person_name' => $u['person_name'] ?? null,
                            'size_label' => $u['size_label'] ?? null,
                            'chest' => $u['chest'] ?? null,
                            'waist' => $u['waist'] ?? null,
                            'hip' => $u['hip'] ?? null,
                            'length' => $u['length'] ?? null,
                            'unit_price' => $u['unit_price'] ?? null,
                        ]);
                        $count++;
                        if (isset($u['unit_price']) && $u['unit_price'] !== '') { $unitTotal += (float) $u['unit_price']; }
                    }
                }
                if (str_contains($mi->item_type, 'Jersey')) { $mi->quantity = max(1, $count); }
                $mi->total_price = $unitTotal > 0 ? $unitTotal : ($mi->quantity * (float) $mi->price_per_item);
                $mi->saveQuietly();
            }
        });

        return redirect()->route('measurements.index')
                         ->with('success', 'Measurement updated successfully!');
    }

    /**
     * Remove the specified measurement from storage.
     */
    public function destroy(string $id)
    {
        $measurement = Measurement::findOrFail($id);
        $measurement->delete();

        return redirect()->route('measurements.index')
                         ->with('success', 'Measurement deleted successfully!');
    }
}
