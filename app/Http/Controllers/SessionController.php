<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\Guest;
use App\Models\SubGuest;
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
        'people_count' => 1,
        'session_type' => 'regular',
        'room_number' => null,
    ]);

    $session->subGuests()->create([
    'name' => $guest->fullname,
    'joined_at' => now(),
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
    // $hours = $durationMinutes / 60;
    // $billAmount = 0;
    // $grace = 0.25;

    // switch (true) {

    //     case ($hours < 1 + $grace):
    //         $billAmount = 25;
    //         break;

    //     case ($hours >= 1 && $hours < 3 + $grace):
    //         $billAmount = 50;
    //         break;

    //     case ($hours >= 3 && $hours < 6 + $grace):
    //         $billAmount = 80;
    //         break;

    //     case ($hours >= 6 && $hours < 8 + $grace):
    //         $billAmount = 100;
    //         break;

    //     case ($hours >= 8 && $hours < 12 + $grace):
    //         $billAmount = 120;
    //         break;

    //     case ($hours >= 12 + $grace && $hours <= 24):
    //         $billAmount = 150;
    //         break;

    //     default:
    //         $billAmount = 1;
    //         break;
    // }
    // $billAmount = $billAmount * $session->people_count;
// الحساب الأساسي
if ($session->session_type === 'regular') {

    foreach ($session->subGuests()->whereNull('left_at')->get() as $sg) {
        $sg->update(['left_at' => now()]);
    }

    $baseBill = $this->calculateRegularFromSubGuests($session);

} else {

    $baseBill = $this->calculateSessionBill($session);
}

// ✅ طبّق الخصم
$discountResult = $this->applySessionDiscount($session, $baseBill);

// ✅ احفظ الرقم النهائي بعد الخصم
$session->update([
    'check_out' => $checkOut,
    'duration_minutes' => $durationMinutes,
    'bill_amount' => $discountResult['final'],
]);


    return redirect()->back()->with('success', 'Session ended successfully!');
}

public function addSubGuest(Request $request, Session $session)
{
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    // ممنوع إضافة SubGuest لو السيشن Room
    if ($session->session_type === 'room') {
        return response()->json([
            'status' => 'error',
            'message' => 'Sub guests are not allowed in room sessions'
        ], 422);
    }

    $subGuest = $session->subGuests()->create([
        'name' => $request->name,
        'joined_at' => now(),
    ]);

    // تحديث people_count (لسه مؤقت)
    $session->update([
        'people_count' => $session->subGuests()->active()->count()
    ]);

    return response()->json([
        'status' => 'success',
        'sub_guest' => $subGuest
    ]);
}

public function endSubGuest(SubGuest $subGuest)
{
    if ($subGuest->left_at !== null) {
        return response()->json([
            'status' => 'error',
            'message' => 'Sub guest already ended'
        ], 422);
    }

    $subGuest->update([
        'left_at' => now(),
    ]);

    // تحديث people_count
    $session = $subGuest->session;
    $session->update([
        'people_count' => $session->subGuests()->active()->count()
    ]);

    return response()->json([
        'status' => 'success'
    ]);
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
    $grace = 0.25;
    // حساب الفاتورة للسيشن (نفس المنطق اللي عندك)
    switch (true) {

        // 1 → 3.5
        case ($hoursFloat >= 1 && $hoursFloat < (3 + $grace)):
            $bill = 50;
            break;
    
        // 3 → 6.5
        case ($hoursFloat >= 3 && $hoursFloat < (6 + $grace)):
            $bill = 80;
            break;
    
        // 6 → 12.5
        case ($hoursFloat >= 6 && $hoursFloat < (8 + $grace)):
            $bill = 100;
            break;

        case ($hoursFloat >= 8 && $hoursFloat < (12 + $grace)):
            $bill = 120;
            break;
    
        // 12.5 → 24
        case ($hoursFloat >= (12 + $grace) && $hoursFloat <= 24):
            $bill = 120;
            break;
    
        // default
        default:
            $bill = 1;
            break;
    }
    // $bill = $bill * $session->people_count;
    // $bill = $this->calculateSessionBill($session);
    if ($session->session_type === 'regular') {

    // اقفل أي SubGuest لسه Active (للمعاينة بس)
    foreach ($session->subGuests->whereNull('left_at') as $sg) {
        $sg->left_at = now();
    }

    $bill = $this->calculateRegularFromSubGuests($session);

} else {
    // room session زي ما هي
    $bill = $this->calculateSessionBill($session);
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

        // فقط الأوردرات اللي حالتهم Received يضيفوا للمجموع
        if ($order->status === 'Received') {
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

    // $grandTotal = round($bill + $drinksTotal, 2);
    $sessionResult = $this->applySessionDiscount($session, $bill);

    $billOriginal = $sessionResult['original'];
    $billDiscount = $sessionResult['discount'];
    $billFinal    = $sessionResult['final'];

    $grandTotal = round($billFinal + $drinksTotal, 2);


    $subGuestsBreakdown = [];

if ($session->session_type === 'regular') {

    foreach ($session->subGuests as $sg) {

        $in  = \Carbon\Carbon::parse($sg->joined_at);
        $out = $sg->left_at
            ? \Carbon\Carbon::parse($sg->left_at)
            : \Carbon\Carbon::now();

        $diff = $in->diff($out);
        $hours = ($diff->days * 24) + $diff->h + ($diff->i / 60);
        $grace = 0.5;

        // نفس أسعار regular
        if ($hours < 1 + $grace) {
            $price = 25;
        } elseif ($hours < 3 + $grace) {
            $price = 50;
        } elseif ($hours < 6 + $grace) {
            $price = 80;
        } elseif ($hours < 8 + $grace) {
            $price = 100;
        } elseif ($hours < 12 + $grace) {
            $price = 120;
        } else {
            $price = 150;
        }

        $subGuestsBreakdown[] = [
            'name'     => $sg->name,
            'duration' => $diff->h . 'h ' . $diff->i . 'm',
            'price'    => $price,
        ];
    }
}


    return view('gest.check', compact(
  'session',
  'duration',
  'billOriginal',
  'billDiscount',
  'billFinal',
  'drinksTotal',
  'drinksDetails',
  'grandTotal',
  'subGuestsBreakdown'
));

}

public function updatePeople(Request $request, Session $session)
{
    $request->validate([
        'people_count' => 'required|integer|min:1|max:20',
    ]);

    $session->update([
        'people_count' => $request->people_count
    ]);

    return response()->json(['success' => true]);
}

public function updateType(Request $request, Session $session)
{
    $request->validate([
        'session_type' => 'required|in:regular,room'
    ]);

    $session->update([
        'session_type' => $request->session_type,
        'room_number' => $request->session_type === 'regular' ? null : $session->room_number
    ]);

    return response()->json(['success' => true]);
}

public function updateRoom(Request $request, Session $session)
{
    $request->validate([
        'room_number' => 'nullable|integer|in:1,2,3,4'
    ]);

    $session->update([
        'room_number' => $request->room_number
    ]);

    return response()->json(['success' => true]);
}


public function calculateSessionBill(Session $session)
{
    // 1️⃣ احسب عدد الساعات
    $checkIn = \Carbon\Carbon::parse($session->check_in);
    $checkOut = $session->check_out
        ? \Carbon\Carbon::parse($session->check_out)
        : \Carbon\Carbon::now();

    $duration = $checkIn->diff($checkOut);
    $hours = ($duration->days * 24) + $duration->h + ($duration->i / 60);
    $grace = 0.50;

    $bill = 0;

    // 2️⃣ Regular pricing (نفس الكود القديم حرفيًا)
    if ($session->session_type === 'regular') {

        switch (true) {
            case ($hours < 1 + $grace):
                $bill = 25;
                break;
            case ($hours >= 1 && $hours < 3 + $grace):
                $bill = 50;
                break;
            case ($hours >= 3 && $hours < 6 + $grace):
                $bill = 80;
                break;
            case ($hours >= 6 && $hours < 8 + $grace):
                $bill = 100;
                break;
            case ($hours >= 8 && $hours < 12 + $grace):
                $bill = 120;
                break;
            case ($hours >= 12 + $grace):
                $bill = 150;
                break;
            default:
                $bill = 1;
        }

        // 👈 regular بس هو اللي يتضرب في عدد الأشخاص
        $bill = $bill * $session->people_count;
    }

    // 3️⃣ Room pricing (جديد – منفصل)
    if ($session->session_type === 'room') {

    $grace = 0.5; // ⏱️ نص ساعة سماح

    $billableHours = floor($hours);

    if (($hours - $billableHours) > $grace) {
        $billableHours++;
    }

    $billableHours = max(1, $billableHours);

    switch ($session->room_number) {

        case 1:
        case 2:
            $bill = 200 + max(0, $billableHours - 1) * 150;
            break;

        case 3:
            $bill = 250 + max(0, $billableHours - 1) * 200;
            break;

        case 4:
            $bill = 350 + max(0, $billableHours - 1) * 300;
            break;

        default:
            $bill = 0;
    }
}


    return $bill;
}

public function calculateRegularFromSubGuests(Session $session)
{
    $total = 0;

    // نجيب بس الـ sub guests اللي اتقفلوا
    $subGuests = $session->subGuests()
        ->whereNotNull('left_at')
        ->get();

    foreach ($subGuests as $subGuest) {

        $checkIn = \Carbon\Carbon::parse($subGuest->joined_at);
        $checkOut = \Carbon\Carbon::parse($subGuest->left_at);

        $duration = $checkIn->diff($checkOut);
        $hours = ($duration->days * 24) + $duration->h + ($duration->i / 60);
        $grace = 0.50;

        $bill = 0;

        // 🔴 نفس أسعار regular الحالية حرفيًا
        switch (true) {
            case ($hours < 1 + $grace):
                $bill = 25;
                break;

            case ($hours >= 1 && $hours < 3 + $grace):
                $bill = 50;
                break;

            case ($hours >= 3 && $hours < 6 + $grace):
                $bill = 80;
                break;

            case ($hours >= 6 && $hours < 8 + $grace):
                $bill = 100;
                break;

            case ($hours >= 8 && $hours < 12 + $grace):
                $bill = 120;
                break;

            case ($hours >= 12 + $grace):
                $bill = 150;
                break;

            default:
                $bill = 1;
        }

        $total += $bill;
    }

    return $total;
}

private function applySessionDiscount(Session $session, float $amount): array
{
    $discount = 0;

    if ($session->discount_value) {
        if ($session->discount_type === 'percent') {
            $discount = ($amount * $session->discount_value) / 100;
        } else {
            $discount = $session->discount_value;
        }
    }

    $final = max(0, $amount - $discount);

    return [
        'original' => $amount,
        'discount' => round($discount, 2),
        'final'    => round($final, 2),
    ];
}


public function updateDiscount(Request $request, Session $session)
{
    $request->validate([
        'discount_value' => 'nullable|numeric|min:0',
        'discount_reason' => 'nullable|string|max:1000',
    ]);

    $session->update([
        'discount_type'   => 'fixed', // دلوقتي ثابت
        'discount_value'  => $request->discount_value,
        'discount_reason' => $request->discount_reason,
    ]);

    return response()->json([
        'status' => 'success'
    ]);
}





}
