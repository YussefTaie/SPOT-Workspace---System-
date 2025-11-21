<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\Guest;
use Illuminate\Http\Request;


class SessionController extends Controller
{
    public function index()
    {
        $sessions = Session::with('guest', 'orders.menuItem')->get();
        return view('sessions.index', compact('sessions'));
    }

    public function create()
    {
        $guests = Guest::all();
        return view('sessions.create', compact('guests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'table_number' => 'required',
            'check_in' => 'required|date',
        ]);

        Session::create($request->all());
        return redirect()->route('sessions.index')->with('success', 'Session created successfully');
    }

    public function show(Session $session)
    {
        $session->load('guest', 'orders.menuItem');
        return view('sessions.show', compact('session'));
    }

    public function edit(Session $session)
    {
        $guests = Guest::all();
        return view('sessions.edit', compact('session', 'guests'));
    }

    public function update(Request $request, Session $session)
    {
        $session->update($request->all());
        return redirect()->route('sessions.index')->with('success', 'Session updated successfully');
    }

    public function destroy(Session $session)
    {
        $session->delete();
        return redirect()->route('sessions.index')->with('success', 'Session deleted successfully');
    }

    public function scan(Request $request)
{
    // مثال: الماكينة بتبعت ?guest_id=5
    $guestId = $request->get('guest_id');

    $guest = Guest::find($guestId);

    if (!$guest) {
        return response()->json([
            'status' => 'error',
            'message' => 'Guest not found',
        ], 404);
    }

    // Check لو الجست عنده Session مفتوحة
    $activeSession = Session::where('guest_id', $guest->id)
    ->whereNull('check_out')
    ->first();

    if ($activeSession) {
        return response()->json([
            'status' => 'error',
            'message' => 'Guest already checked in',
        ]);
    }

    // إنشاء Session جديدة
    $session = Session::create([
        'guest_id' => $guest->id,
        'table_number' => 1, // أو خليه يجي من request
        'check_in' => now(),
        'rate_per_hour' => 60,
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Guest checked in successfully',
        'session' => $session,
        'guest' => $guest,
    ]);
}



public function endSession(Session $session)
{
    // احسب الـ duration
    $checkIn = \Carbon\Carbon::parse($session->check_in);
    $checkOut = \Carbon\Carbon::now();
    $durationMinutes = $checkIn->diffInMinutes($checkOut);

    // احسب الفاتورة بناءً على rate_per_hour
    $hours = $durationMinutes / 60;
    $billAmount = 0;

    switch (true) {
        case ($hours >= 1 && $hours < 3):
            $billAmount = 50;
            break;

        case ($hours >= 3 && $hours < 6):
            $billAmount = 80;
            break;

        case ($hours >= 6 && $hours < 12):
            $billAmount = 100;
            break;

        case ($hours >= 12 && $hours <= 24):
            $billAmount = 120;
            break;

        default:
            $billAmount = 1; // لو أقل من ساعة مثلاً أو خطأ
            break;
    }


    // حدث بيانات السيشن
    $session->update([
        'check_out' => $checkOut,
        'duration_minutes' => $durationMinutes,
        'bill_amount' => $billAmount,
    ]);

    return redirect()->back()->with('success', 'Session ended successfully!');
}



public function check($id)
{
    // جلب السيشن مع الجيست والأوردرات والمنتجات
    $session = \App\Models\Session::with(['guest','orders.menuItem'])->findOrFail($id);

    // نحسب الديوريشن
    $checkIn = \Carbon\Carbon::parse($session->check_in);
    $checkOut = $session->check_out ? \Carbon\Carbon::parse($session->check_out) : \Carbon\Carbon::now();
    $duration = $checkIn->diff($checkOut);
    // عدد الساعات كفواصل (مثال: 1.5 ساعة)
    $hoursFloat = ($duration->days * 24) + $duration->h + ($duration->i / 60);

    // حساب الفاتورة للسيشن (نفس المنطق اللي عندك)
    switch (true) {
        case ($hoursFloat >= 1 && $hoursFloat < 3):
            $bill = 50;
            break;
        case ($hoursFloat >= 3 && $hoursFloat < 6):
            $bill = 80;
            break;
        case ($hoursFloat >= 6 && $hoursFloat < 12):
            $bill = 100;
            break;
        case ($hoursFloat >= 12 && $hoursFloat <= 24):
            $bill = 120;
            break;
        default:
            $bill = 1;
            break;
    }

    // نجيب كل الأوردرات (ما عدا الملغية) عشان نعرض تفاصيلها،
    // لكن لما نحسب المجموع هنأخد بس ال Done
    $orders = $session->orders->whereNotIn('status', ['Canceled']);

    $drinksTotal = 0;
    $drinksDetails = [];

    foreach ($orders as $order) {
        // حساب unitPrice و subtotal (fallbacks آمنة)
        if (!is_null($order->total_price)) {
            $subtotal = (float) $order->total_price;
            $unitPrice = $order->unit_price ?? ($order->quantity ? $subtotal / $order->quantity : 0);
        } else {
            $unitPrice = $order->unit_price ?? (optional($order->menuItem)->price ?? 0);
            $qty = $order->quantity ?? 1;
            $subtotal = $unitPrice * $qty;
        }

        $qty = $order->quantity ?? 1;

        // فقط الأوردرات اللي حالتهم Done يضيفوا للمجموع
        if ($order->status === 'Done') {
            $drinksTotal += $subtotal;
        }

        $drinksDetails[] = [
            'name' => optional($order->menuItem)->name ?? 'Item #' . $order->menu_item_id,
            'price' => $unitPrice,
            'qty' => $qty,
            'subtotal' => $subtotal,
            'status' => $order->status,
        ];
    }

    $grandTotal = round($bill + $drinksTotal, 2);

    return view('gest.check', compact('session', 'duration', 'bill', 'drinksTotal', 'drinksDetails', 'grandTotal'));
}






}
