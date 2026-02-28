<?php

namespace App\Presenters;

use App\Models\Session;
use Carbon\Carbon;

class GuestSessionPresenter
{
    /**
     * Snapshot لسشن شغالة دلوقتي
     * 
     * @param Session $session
     * @param float $grandTotal - Calculated bill from SessionController::calculateSessionBill()
     * @return array
     */
    public static function live(Session $session, float $grandTotal): array
    {
        $checkIn = Carbon::parse($session->check_in);
        $now = now();

        $minutes = $checkIn->diffInMinutes($now);
        $duration = floor($minutes / 60) . 'h ' . ($minutes % 60) . 'm';

        return [
            'session_type' => $session->session_type,
            'room_number'  => $session->room_number,
            'people_count' => $session->people_count ?? $session->subGuests->count() + 1,
            'started_at'   => $checkIn->format('H:i'),
            'duration'     => $duration,

            // Use the passed calculated value - single source of truth
            'grand_total'  => $grandTotal,

            'has_orders'   => $session->orders()->exists(),
        ];

    }

    /**
     * History row لسشن خلصانة
     */
   public static function history(Session $session): array
{
    $checkIn  = \Carbon\Carbon::parse($session->check_in);
    $checkOut = $session->check_out
        ? \Carbon\Carbon::parse($session->check_out)
        : null;

    $minutes = $checkOut
        ? $checkIn->diffInMinutes($checkOut)
        : 0;

    $duration = floor($minutes / 60) . 'h ' . ($minutes % 60) . 'm';

    // 🔥 الحقايق من السيستم
    $sessionAfterDiscount = (float) $session->bill_amount;   // 100
    $discount             = (float) ($session->discount_value ?? 0); // 60
    $sessionFee           = $sessionAfterDiscount + $discount; // 160
    $drinksTotal           = (float) $session->orders->sum('total_price'); // 125

    $grandTotal = $sessionAfterDiscount + $drinksTotal; // 225 ✅

    return [
        // Header
        'started_at' => $checkIn->format('d/m/Y H:i'),
        'ended_at'   => $checkOut?->format('d/m/Y H:i'),
        'duration'   => $duration,

        // People
        'sub_guests' => $session->subGuests
            ->map(fn ($g) => $g->name)
            ->values(),

        // Orders
        'orders' => $session->orders->map(function ($order) {
            return [
                'name'  => $order->menuItem->name,
                'price' => (float) $order->total_price,
            ];
        }),

        // Billing (مطابق 100% للشيك)
        'session_fee'            => $sessionFee,             // 160
        'discount'               => $discount,               // 60
        'discount_reason'        => $session->discount_reason,
        'session_after_discount' => $sessionAfterDiscount,   // 100
        'drinks_total'           => $drinksTotal,             // 125
        'grand_total'            => $grandTotal,              // 225 ✅
    ];
}





}
