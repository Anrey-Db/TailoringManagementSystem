<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'measurement_type',

        // Shirt
        'shirt_neck', 'shirt_shoulder', 'shirt_chest', 'shirt_waist', 'shirt_sleeve_length', 'shirt_length',

        // Jersey
        'jersey_neck', 'jersey_shoulder', 'jersey_chest', 'jersey_waist', 'jersey_length',

        // Jersey Short
        'jersey_short_waist', 'jersey_short_hip', 'jersey_short_length', 'jersey_short_bottom',

        // Coat (Up)
        'coat_shoulder', 'coat_body', 'coat_waist', 'coat_armhole', 'coat_sleeve_length', 'coat_length',

        // Coat (Down)
        'coat_pants_waist', 'coat_pants_hip', 'coat_pants_legs', 'coat_pants_length', 'coat_pants_bottom',

        // Uniform
        'uniform_shoulder', 'uniform_chest', 'uniform_waist', 'uniform_hip', 'uniform_length',
        'uniform_sleeve_length', 'uniform_pants_length', 'uniform_pants_waist',
        'uniform_pants_hip', 'uniform_pants_bottom',

        // PE Uniform
        'pe_uniform_shoulder', 'pe_uniform_chest', 'pe_uniform_waist', 'pe_uniform_length',
        'pe_uniform_sleeve_length', 'pe_uniform_pants_length', 'pe_uniform_pants_waist',
        'pe_uniform_pants_hip', 'pe_uniform_pants_bottom',

        // Remarks
        'remarks',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function items()
    {
        return $this->hasMany(MeasurementItem::class);
    }
}
