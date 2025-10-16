<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeasurementItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'measurement_id', 'item_type', 'quantity', 'price_per_item', 'total_price', 'description', 'notes'
    ];

    protected $casts = [
        'price_per_item' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function measurement()
    {
        return $this->belongsTo(Measurement::class);
    }

    public function units()
    {
        return $this->hasMany(MeasurementItemUnit::class);
    }
}


