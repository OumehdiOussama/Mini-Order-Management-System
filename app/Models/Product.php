<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model {
    use HasFactory;

    protected $fillable = ['name', 'price'];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function orders() {
        return $this->belongsToMany(Order::class)->withPivot('quantity');
    }
}