<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;

class OrderSyncController extends Controller
{
    public function partials(Request $request)
    {
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
        ->orderByDesc('updated_at')
        ->get();



        $receivedOrders = Order::whereRaw("LOWER(status) = ?", ['received'])
        ->with(['session.guest','menuItem'])
        ->orderByDesc('updated_at')
        ->get();




        $pendingHtml = view('barista.partials.pending_tbody', compact('pendingOrders'))->render();
        $inProgressHtml = view('barista.partials.inprogress_tbody', compact('inProgressOrders'))->render();
        $doneHtml = view('barista.partials.done_tbody', compact('doneOrders'))->render();
        $receivedHtml = view('barista.partials.received_tbody', compact('receivedOrders'))->render();


        return response()->json([
            'pending'    => $pendingHtml,
            'inProgress' => $inProgressHtml,
            'done'       => $doneHtml,
            'received'   => $receivedHtml,
            'timestamp'  => now()->toISOString(),
        ])->header('Cache-Control','no-store, no-cache, must-revalidate, max-age=0');

    }
}

