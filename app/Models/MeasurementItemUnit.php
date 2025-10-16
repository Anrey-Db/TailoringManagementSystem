<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeasurementItemUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'measurement_item_id', 'person_name', 'size_label', 'chest', 'waist', 'hip', 'length', 'unit_price'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
    ];

    public function item()
    {
        return $this->belongsTo(MeasurementItem::class, 'measurement_item_id');
    }
}


