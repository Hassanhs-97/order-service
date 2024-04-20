<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_name',
        'customer_address',
        'order_description',
        'total_price',
    ];

    protected $appends = ['formatted_created_at'];


    public function items() {
        return $this->belongsToMany(Item::class, 'order_items');
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }

    public static function calculateTotalItemPrice($itemPrice, $itemCount) {
        return $itemPrice * $itemCount;
    }

    public function getFormattedCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
    }
}
