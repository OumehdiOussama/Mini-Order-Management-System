<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model {
    use HasFactory;

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

    public function timeline() {
        return $this->hasMany(OrderTimeline::class)->orderBy('created_at', 'desc');
    }

    /**
     ! Get total price for this order
     */
    public function getTotalPrice()
    {
        return $this->products->reduce( function($carry, $product) {
            return $carry + ($product->price * $product->pivot->quantity);
        }, 0);
    }

    /**
     ! Add a status change to the timeline
     */
    public function addTimeline($status, $notes = null, $trackingNumber = null, $carrier = null)
    {
        return $this->timeline()->create([
            'status' => $status,
            'notes' => $notes,
            'tracking_number' => $trackingNumber,
            'carrier' => $carrier,
        ]);
    }
}