<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_id',
        'payment_method',
        'amount_paid',
        'payment_date',
        'reference_number',
        'payment_reference', // Keep both for compatibility
        'amount', // Old field name for compatibility
        'method', // Old field name for compatibility
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'amount' => 'decimal:2', // Old field name for compatibility
        'payment_date' => 'date',
    ];

    // Relationship
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
