<?php

namespace App\Http\Controllers\Barista;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;

class OrderSyncController extends Controller
{
    public function partials(Request $request)
    {
        // Normalize statuses depending on how you store them (case-sensitive differences)
        $pendingOrders = Order::whereRaw("LOWER(status) = ?", ['pending'])
            ->with(['session.guest','menuItem'])
            ->orderBy('created_at')
            ->get();

        $inProgressOrders = Order::whereRaw("LOWER(status) IN (?,?)", ['inprogress','in_progress'])
            ->with(['session.guest','menuItem'])
            ->orderBy('accepted_at')
            ->get();

        $doneOrders = Order::whereRaw("LOWER(status) = ?", ['done'])
            ->with(['session.guest','menuItem'])
            ->whereDate('served_at', Carbon::today())
            ->orderByDesc('served_at')
            ->get();

        $pendingHtml = view('barista.partials.pending_tbody', compact('pendingOrders'))->render();
        $inProgressHtml = view('barista.partials.inprogress_tbody', compact('inProgressOrders'))->render();
        $doneHtml = view('barista.partials.done_tbody', compact('doneOrders'))->render();

        return response()->json([
            'pending'   => $pendingHtml,
            'inProgress'=> $inProgressHtml,
            'done'      => $doneHtml,
            'timestamp' => now()->toISOString(),
        ])->header('Cache-Control','no-store, no-cache, must-revalidate, max-age=0');
    }
}
