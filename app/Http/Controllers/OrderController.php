<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
    // لو عندك دوال resource أخرى فاختفيها هنا — هذا ملف كامل يحتوي على الدوال المطلوبة.
    // اضف باقي دوال index/create/store/edit/update/destroy لو محتاج.

    /**
     * Accept an order (Pending -> InProgress)
     */

     public function store(Request $request)
{
    $request->validate([
        'guest_id' => 'required|integer|exists:guests,id',
        'cart' => 'required|array|min:1',
        'cart.*.id' => 'required|integer|exists:menu_items,id',
        'cart.*.qty' => 'required|integer|min:1',
    ]);

    $guestId = (int) $request->input('guest_id');

    // جلب السيشن النشطة للـ guest (لو موجودة)
    $existingSession = \App\Models\Session::where('guest_id', $guestId)
        ->whereNull('check_out')
        ->latest()
        ->first();

    if (!$existingSession) {
        if (! $request->boolean('takeaway', false)) {
            return response()->json(['status' => 'error', 'message' => 'No active session found for this guest'], 400);
        }
    }

    // detect staff id from pseudo-guest email if present
    $guestModel = \App\Models\Guest::find($guestId);
    $staffIdToAttach = null;
    if ($guestModel && !empty($guestModel->email)) {
        if (preg_match('/^staff_(\d+)@internal\.local$/', $guestModel->email, $m)) {
            $staffIdToAttach = (int) $m[1];
        }
    }

    $takeawayFlag = $request->boolean('takeaway', false);

    DB::beginTransaction();
    try {
        // If this is a barista takeaway, create a dedicated (closed) session so it appears in admin checkout history
        $sessionToUse = $existingSession;
        $isTakeawaySessionCreated = false;
        if ($staffIdToAttach && $takeawayFlag) {
            // create a short session with check_in/check_out now; bill_amount stays 0 for now
            $sessionToUse = \App\Models\Session::create([
                'guest_id' => $guestId,
                'table_number' => null,
                'check_in' => now(),
                'check_out' => now(),
                'duration_minutes' => 0,
                'rate_per_hour' => 0,
                'bill_amount' => 0, // <-- important: do NOT pre-fill bill_amount
            ]);
            $isTakeawaySessionCreated = true;
        }

        $totalForSession = 0;

        foreach ($request->cart as $item) {
            $menu = MenuItem::find($item['id']);
            $unitPrice = $menu ? (float)$menu->price : 0.00;
            $quantity = (int)$item['qty'];
            $totalPrice = $unitPrice * $quantity;
            $totalForSession += $totalPrice;

            Order::create([
                'session_id' => $sessionToUse->id,
                'menu_item_id' => $item['id'],
                'quantity' => $quantity,
                'status' => 'Pending',
                'ordered_by' => $guestId,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'staff_id' => $staffIdToAttach,
                'takeaway' => $takeawayFlag,
            ]);
        }

        // DON'T set session->bill_amount here for takeaway sessions.
        // The single source-of-truth for billing stays the markDone() flow which increments when orders are marked Done.

        DB::commit();
        return response()->json(['status' => 'success', 'message' => 'Order placed successfully']);
    } catch (\Throwable $e) {
        DB::rollBack();
        \Log::error('Order store exception: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
        return response()->json(['status' => 'error', 'message' => 'Server error while placing order'], 500);
    }
}



     
public function accept(Request $request, Order $order)
{
    // قبول الأوردر لازم يكون Pending
    if (! in_array(strtolower($order->status), ['pending'])) {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status'=>'error','message'=>'Order not pending'], 409);
        }
        return redirect()->back()->with('error', 'Order not pending');
    }

    $order->status = 'InProgress';
    $order->accepted_at = now();
    $order->staff_id = auth()->id() ?? $order->staff_id;
    $order->save();

    if ($request->ajax() || $request->wantsJson()) {
        return response()->json(['status'=>'ok','message'=>'Order accepted']);
    }
    return redirect()->back()->with('success','Order accepted');
}


    /**
     * Mark order done (InProgress -> Done)
     */
    public function markDone(Request $request, Order $order)
{
    if (! in_array(strtolower($order->status), ['inprogress','in_progress'])) {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Order not in progress'
            ], 409);
        }

        return redirect()->back()->with('error', 'Order not in progress');
    }

    DB::table('orders')
        ->where('id', $order->id)
        ->update([
            'status' => 'Done'
        ]);

    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'status' => 'ok',
            'message' => 'Order marked as done'
        ]);
    }

    return redirect()->back()->with('success', 'Order marked as done');
}



    /**
     * Cancel an order (any status -> Canceled)
     */
    public function cancel(Request $request, Order $order)
{
    DB::transaction(function() use ($order) {
        $wasReceived = strtolower($order->status) === 'received';
        $total = $order->total_price ?? ($order->unit_price * ($order->quantity ?? 1));

        $order->status = 'Canceled';
        $order->save();

        if ($wasReceived && Schema::hasColumn('sessions', 'bill_amount')) {
            $order->session()->decrement('bill_amount', $total);
        }
    });

    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'status' => 'ok',
            'message' => 'Order canceled',
            'refresh_guest' => true
        ]);

    }
    return redirect()->back()->with('success','Order canceled');
}


    // --- optional: index/show methods if you want a RESTful full controller ---
    public function index()
    {
        $orders = Order::with(['session.guest','menuItem','staff'])->orderBy('created_at','desc')->get();
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['session.guest','menuItem','staff']);
        return view('orders.show', compact('order'));
    }

    public function markReceived(Request $request, Order $order)
{
    if (strtolower($order->status) !== 'done') {
        return response()->json([
            'status'  => 'error',
            'message' => 'Order not done yet'
        ], 409);
    }

    DB::transaction(function () use ($order) {

        $total = $order->total_price
            ?? ($order->unit_price * ($order->quantity ?? 1));

        DB::table('orders')
            ->where('id', $order->id)
            ->update([
                'status' => 'Received'
            ]);

        DB::table('sessions')
            ->where('id', $order->session_id)
            ->increment('bill_amount', $total);
    });

    return response()->json([
        'status' => 'ok',
        'message' => 'Order received and billed'
    ]);
}




    
}
