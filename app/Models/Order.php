<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_address',
        'order_description',
        'total_price',
    ];

    public function items() {
        return $this->belongsToMany(Item::class, 'order_items');
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }
}
