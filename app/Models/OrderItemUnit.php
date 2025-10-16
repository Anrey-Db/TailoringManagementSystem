<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'measurement_id',
        'person_name',
        'size_label',
        'chest',
        'waist',
        'hip',
        'length',
        'unit_price',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function measurement()
    {
        return $this->belongsTo(Measurement::class);
    }
}


