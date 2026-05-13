<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model {
    use HasFactory, LogsActivity;

    public const STATUSES = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

    private const STATUS_TRANSITIONS = [
        'pending' => ['processing', 'cancelled'],
        'processing' => ['shipped', 'cancelled'],
        'shipped' => ['delivered', 'cancelled'],
        'delivered' => [],
        'cancelled' => [],
    ];

    protected $fillable = [
        'customer_id',
        'status',
        'total_amount',
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
        return $this->hasMany(OrderTimeline::class)->orderBy('created_at');
    }

    public function canTransitionTo(string $nextStatus): bool
    {
        if ($nextStatus === $this->status) {
            return true;
        }

        return in_array($nextStatus, self::STATUS_TRANSITIONS[$this->status] ?? [], true);
    }

    public function availableTransitionStatuses(): array
    {
        $allowedStatuses = self::STATUS_TRANSITIONS[$this->status] ?? [];

        return array_values(array_unique(array_merge([$this->status], $allowedStatuses)));
    }

    /**
     * Get total price for this order.
     */
    public function getTotalPrice()
    {
        return $this->products->reduce(function ($carry, $product) {
            return $carry + ($product->price * $product->pivot->quantity);
        }, 0);
    }

    /**
     * Add a status change to the timeline.
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
