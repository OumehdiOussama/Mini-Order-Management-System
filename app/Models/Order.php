<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    protected $fillable = [
        'customer_id',
        'status',
        'tracking_number',
        'carrier'
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function products() {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }
}