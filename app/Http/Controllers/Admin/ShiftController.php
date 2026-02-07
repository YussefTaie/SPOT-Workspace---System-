<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ShiftService;
use App\Models\Shift;
use App\Models\Session;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function change(Request $request)
{
    $activeShift = Shift::whereNull('ended_at')
        ->orderByDesc('started_at')
        ->first();

    if (!$activeShift) {
        abort(500, 'No active shift found');
    }

    // احسب الإجمالي (زي ما هو)
    $total = Session::where('shift_id', $activeShift->id)
        ->get()
        ->sum(function ($session) {

            $drinksTotal = $session->orders
                ->where('status', 'Received')
                ->sum(function ($order) {
                    return $order->total_price
                        ?? (($order->unit_price ?? 0) * ($order->quantity ?? 1));
                });

            return ($session->bill_amount ?? 0) + $drinksTotal;
        });

    // خزّن الإجمالي بس
    $activeShift->update([
        'total_amount' => $total,
        'closed_by'    => auth('admin')->id(),
    ]);

    // 👈 سيب القفل والفتح للـ service
    app(\App\Services\ShiftService::class)->forceChangeShift();

    return redirect()->back()->with('success', 'Shift changed successfully');
}


}
