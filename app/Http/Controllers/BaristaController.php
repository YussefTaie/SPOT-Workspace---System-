<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Guest;
use App\Models\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BaristaController extends Controller
{
    // KEEP your existing dashboard method (I include it unchanged)
    public function Baristadashboard()
{
    // eager load relations we need
    $baseQuery = Order::with(['session.guest', 'menuItem', 'staff']);

    $hasAcceptedAt = \Illuminate\Support\Facades\Schema::hasColumn('orders', 'accepted_at');
    $hasServedAt   = \Illuminate\Support\Facades\Schema::hasColumn('orders', 'served_at');

    $pendingOrders = (clone $baseQuery)
        ->where('status', 'Pending')
        ->orderBy('created_at', 'asc')
        ->get();

    $inProgressOrders = (clone $baseQuery)
        ->where('status', 'InProgress')
        ->orderBy($hasAcceptedAt ? 'accepted_at' : 'updated_at', 'asc')
        ->get();

    $doneOrders = (clone $baseQuery)
        ->where('status', 'Done')
        ->orderBy($hasServedAt ? 'served_at' : 'updated_at', 'desc')
        ->limit(200)
        ->get();

    // <-- NEW: all recent orders (optional) to satisfy @forelse($orders...)
    $orders = (clone $baseQuery)->orderBy('created_at','desc')->limit(500)->get();

    return view('barista.barista_dashboard', compact('orders','pendingOrders','inProgressOrders','doneOrders'));
}


    /**
     * Helper: get or create a pseudo Guest for this staff (one guest record per staff)
     * Returns the Guest model instance.
     */
    protected function getOrCreateGuestForStaff($staff)
    {
        // Generate an internal email that's unlikely to collide with real guests
        $pseudoEmail = 'staff_' . $staff->id . '@internal.local';

        $guest = Guest::where('email', $pseudoEmail)->first();
        if ($guest) {
            return $guest;
        }

        // create minimal guest record
        $guest = Guest::create([
            'fullname' => $staff->name . ' (staff)',
            'email' => $pseudoEmail,
            'phone' => $staff->phone ?? null,
            // random password since they won't login as guest
            'password' => bcrypt(Str::random(24)),
            'registered_at' => now(),
            'visits_count' => 0,
            'total_time' => 0,
            'total_expenses' => 0,
        ]);

        return $guest;
    }

    /**
     * Show menu for barista — reuse same blade you already have (menu view).
     * We'll pass $guest (pseudo guest) so the front-end JS will send guest_id to orders.store.
     */
    public function menu()
    {
        $staff = Auth::guard('staff')->user() ?? Auth::user();

        if (! $staff) {
            // fallback: require staff auth by middleware; but safe fallback:
            abort(403, 'Forbidden');
        }

        // get or create guest record for this staff
        $guest = $this->getOrCreateGuestForStaff($staff);

        // ensure there's an active session for that guest (OrderController::store expects an active session)
        $session = Session::where('guest_id', $guest->id)
                    ->whereNull('check_out')
                    ->latest()
                    ->first();

        if (! $session) {
            // create a lightweight session so ordering flow works exactly like guest
            $session = Session::create([
                'guest_id' => $guest->id,
                'table_number' => null,
                'check_in' => now(),
                'check_out' => null,
                'duration_minutes' => 0,
                'rate_per_hour' => 0,
                'bill_amount' => 0,
            ]);
        }

        $menuItems = MenuItem::all();

        // Return the same menu blade you already have (the one that expects $menuItems and $guest)
        return view('barista.menu', compact('menuItems', 'guest'));
    }

    /**
     * Optional: show past orders placed by this staff (trace via staff_id)
     */
    public function pastOrders()
    {
        $staff = Auth::guard('staff')->user() ?? Auth::user();
        if (! $staff) abort(403, 'Forbidden');

        $orders = Order::with('menuItem', 'session.guest')
                    ->where('staff_id', $staff->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('barista.orders.past', compact('orders'));
    }

    public function partials()
    {
        $pendingOrders = // query pending...
        $inProgressOrders = // query in-progress...
        $doneOrders = // query done...

        $pendingHtml = view('barista.partials.pending_tbody', compact('pendingOrders'))->render();
        $inProgressHtml = view('barista.partials.inprogress_tbody', compact('inProgressOrders'))->render();
        $doneHtml = view('barista.partials.done_tbody', compact('doneOrders'))->render();

        return response()->json([
            'pending' => $pendingHtml,
            'inProgress' => $inProgressHtml,
            'done' => $doneHtml,
            'timestamp' => now()->toISOString(),
        ]);
    }
}
