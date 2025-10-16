<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'item_type',
        'quantity',
        'price_per_item',
        'total_price',
        'measurement_id',
        'description',
        'notes',
    ];

    protected $casts = [
        'price_per_item' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function measurement()
    {
        return $this->belongsTo(Measurement::class);
    }

    public function units()
    {
        return $this->hasMany(OrderItemUnit::class);
    }

    // Calculate total price automatically
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($orderItem) {
            $orderItem->total_price = $orderItem->quantity * $orderItem->price_per_item;
        });
    }
}
