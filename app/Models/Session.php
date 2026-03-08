<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id',
        'table_number',
        'check_in',
        'check_out',
        'duration_minutes',
        'rate_per_hour',
        'bill_amount',
        'people_count',
        'session_type',
        'room_number',
        'discount_type',
        'discount_value',
        'discount_reason',
        'shift_id', 
        'payment_method',
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function subGuests()
    {
        return $this->hasMany(\App\Models\SubGuest::class);
    }

    // =========================================================
    // Billing — single source of truth
    // =========================================================

    /**
     * Sum of drink orders with status Done or Received.
     * Uses a fresh DB query (not eager-loaded collection)
     * so the result is always accurate.
     */
    public function drinksTotal(): float
    {
        return (float) $this->orders()
            ->whereIn('status', ['Done', 'Received'])
            ->sum('total_price');
    }

    /**
     * Session fee after discount.
     * bill_amount is set by SessionController::endSession() to
     * the discounted session fee. Zero for open/staff sessions.
     */
    public function sessionFee(): float
    {
        return (float) ($this->bill_amount ?? 0);
    }

    /**
     * Grand total = session fee (after discount) + drinks total.
     * This is the canonical total for closed sessions.
     */
    public function grandTotal(): float
    {
        return $this->sessionFee() + $this->drinksTotal();
    }
}
