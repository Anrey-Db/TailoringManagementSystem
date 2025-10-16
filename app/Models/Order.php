<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'measurement_id',
        'order_number',
        'order_date',
        'due_date',
        'status',
        'total_amount',
        'amount_paid',
        'balance',
        'payment_status',
        'remarks',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'due_date' => 'date',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function measurement()
    {
        return $this->belongsTo(Measurement::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Calculate total amount from order items
    public function calculateTotalAmount()
    {
        return $this->orderItems()->sum('total_price');
    }

    // Update order totals when items change
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($order) {
            $totalAmount = $order->calculateTotalAmount();
            $order->updateQuietly(['total_amount' => $totalAmount]);
        });
    }
}
