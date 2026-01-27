<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'pincode',
        'delivery_fee',
        'min_order_amount',
        'estimated_delivery_days',
        'is_active',
    ];

    protected $casts = [
        'delivery_fee' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
