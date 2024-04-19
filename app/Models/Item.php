<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'price',
    ];

    /**
     * Get options forom items.
     *
     * @return array
     */
    public static function selectOptions()
    {
        return self::all()->toArray();
    }

    public function orders() {
        return $this->belongsToMany(Order::class);
    }
}
