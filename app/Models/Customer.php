<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'contact_number',
        'email',
        'address',
        'notes',
    ];

    // A customer can have many measurements
    public function measurements()
    {
        return $this->hasMany(Measurement::class);
    }

    // A customer can have many orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
