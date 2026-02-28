<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Presenters\GuestSessionPresenter;

class GuestController extends Controller
{


    public function profile(Guest $guest)
{
    // جلب كل العلاقات المطلوبة
    $guest->load(['sessions', 'orders.menuItem', 'orders.staff']);

    // آخر جلسة نشطة
    $currentSession = $guest->sessions()->latest()->first();

    $liveSession = null;
    $liveBill = null;

    if ($currentSession && !$currentSession->check_out) {
        $liveBill = app(\App\Http\Controllers\SessionController::class)->calculateSessionBill($currentSession);
        $liveSession = GuestSessionPresenter::live($currentSession, $liveBill);
    }


    // $durationFormatted = '-';
    // $currentBill = 0;

    // if ($currentSession && !$currentSession->check_out) {
    //     $checkIn = \Carbon\Carbon::parse($currentSession->check_in);
    //     $now = \Carbon\Carbon::now();
    //     $duration = $checkIn->diff($now);

    //     // صيغة العرض
    //     $durationFormatted = $duration->h . 'h ' . $duration->i . 'm';

    //     // حساب عدد الساعات كـ float
    //     $hours = $duration->days * 24 + $duration->h + ($duration->i / 60);

    //     $grace = 0.5; // 30 دقيقة

    //     switch (true) {
        
    //         // 1 → 3.5
    //         case ($hours >= 1 && $hours < (3 + $grace)):
    //             $currentBill = 50;
    //             break;
        
    //         // 3 → 6.5
    //         case ($hours >= 3 && $hours < (6 + $grace)):
    //             $currentBill = 80;
    //             break;
        
    //         // 6 → 12.5
    //         case ($hours >= 6 && $hours < (8 + $grace)):
    //             $currentBill = 100;
    //             break;
            
    //         case ($hours >= 8 && $hours < (12 + $grace)):
    //             $currentBill = 120;
    //             break;
        
    //         // 12.5 → 24
    //         case ($hours >= (12 + $grace) && $hours <= 24):
    //             $currentBill = 150;
    //             break;
        
    //         // default = 1
    //         default:
    //             $currentBill = 1;
    //             break;
    //     }
        

    // }


    // الأوردرات الحالية (اللي لسه مش Done)
    $currentOrders = $guest->orders()->whereIn('status', ['Pending', 'InProgress', 'Done'])->latest()->get();

    // كل الأوردرات القديمة
    $historyOrders = $guest->orders()->where('status', 'Done')->latest()->get();

    // حساب الإحصائيات
    $totalVisits = $guest->sessions->count();
    $totalTime = $guest->sessions->sum('duration'); // نفترض duration بالدقايق
    $totalExpenses = $guest->orders->sum('total_price');

    // 🔹 احسب آخر نشاط
    $lastActivity = $guest->sessions()
        ->latest('updated_at')
        ->first()?->updated_at?->diffForHumans() ?? 'No activity yet';

    // 🔹 احسب إجمالي مدة الجلسات
    $totalMinutes = $guest->sessions()
        ->whereNotNull('check_out')
        ->get()
        ->sum(fn($s) => \Carbon\Carbon::parse($s->check_in)->diffInMinutes($s->check_out));

    $totalDuration = floor($totalMinutes / 60) . 'h ' . ($totalMinutes % 60) . 'm';

    // بناء الـ array اللي Blade محتاجه
    $userData = [
        'fullname' => $guest->fullname,
        'phone' => $guest->phone,
        'email' => $guest->email,
        'college' => $guest->college,
        'university' => $guest->university,
        'registered_at' => $guest->created_at->format('d/m/Y'),
        'check_in' => $currentSession?->check_in ?? '-',
        'check_out' => $currentSession?->check_out ?? '-',
        'duration' => $liveSession['duration'] ?? '-',
        'rate' => $currentSession?->rate . ' EGP',
        // 'current_bill' => $currentBill . ' EGP',
        'live_bill' => $liveBill ? $liveBill . ' EGP' : '-',
        'live_session' => $liveSession,
         // 🔹 أضف القيم الجديدة هنا
        'last_activity' => $lastActivity,
        'total_duration' => $totalDuration,

        'visits' => $totalVisits,
        'total_time' => $totalTime . 'm',
        'total_expenses' => $totalExpenses . ' EGP',
        'orders' => $currentOrders->map(function($order){
            return [
                'name' => $order->menuItem->name,
                'time' => $order->created_at->diffForHumans(),
                'status' => $order->status,
                'by' => $order->staff?->name ?? 'N/A',
            ];
        }),
        'history' => $guest->sessions
        ->whereNotNull('check_out')
        ->sortByDesc('check_in')
        ->map(fn ($s) => \App\Presenters\GuestSessionPresenter::history($s))
        ->values(),


        // 'history' => $guest->sessions->map(function($session){
        //     $checkIn = \Carbon\Carbon::parse($session->check_in);
        //     $checkOut = $session->check_out ? \Carbon\Carbon::parse($session->check_out) : now();
        //     $durationMinutes = $checkIn->diffInMinutes($checkOut);
        //     $formattedDuration = floor($durationMinutes / 60) . 'h ' . ($durationMinutes % 60) . 'm';

        //     return [
        //         'date' => $session->created_at->format('d/m/Y'),
        //         'duration' => $formattedDuration,
        //         'bill' => ($session->bill_amount ?? 0) . ' EGP',
        //     ];
        // }),

        // 'history_orders' => $historyOrders->map(function($order){
        //     return [
        //         'date' => $order->created_at->format('d/m/Y'),
        //         'order' => $order->menuItem->name,
        //         'price' => $order->total_price . ' EGP',
        //     ];
        // }),
    ];

    

    return view('gest.profile', compact('userData', 'guest'));
}



    public function index()
    {
        $guests = Guest::with('sessions.orders.menuItem')->get();
        return view('guests.index', compact('guests'));
    }

    public function create()
    {
        return view('gest.gestform');
    }

    public function store(Request $request)
    {
         $request->validate([
        'fullname' => 'required',
        'email' => 'required|email|unique:guests',
        'phone' => 'required',
        'college',
        'university',
        'password' => 'required|min:6|confirmed',
    ]);

    $data = $request->only(['fullname', 'email', 'phone', 'college', 'university', 'password']);
    $data['password'] = bcrypt($data['password']);

    // 1) انشأ الGuest
    $guest = Guest::create($data);

   
    $qrContent = 'http://localhost:8000/scan?guest_id=' . $guest->id;

    // 3) رابط توليد QR — استخدم Google Chart API (رمز & بدون escape)
    $qrUrl = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . urlencode($qrContent);

    // === 4) جلب الصورة باستخدام CURL مع فحص للأخطاء ===
    $ch = curl_init($qrUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // timeout بعد 10 ثواني
    $qrImage = curl_exec($ch);
    $curlErr = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

  
    if ($qrImage === false || $httpCode !== 200) {
        $backupUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrContent);
        $ch2 = curl_init($backupUrl);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch2, CURLOPT_TIMEOUT, 10);
        $qrImage = curl_exec($ch2);
        $httpCode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
        curl_close($ch2);

        if ($qrImage === false || $httpCode2 !== 200) {
            \Log::error('QR generation failed', [
                'original_url' => $qrUrl,
                'curl_error' => $curlErr ?? 'n/a',
                'http_code' => $httpCode ?? 'n/a'
            ]);


            return redirect()->back()->with('error', 'حدث خطأ أثناء توليد QR، حاول مرة تانية.');
        }
    }

    // 5) احفظ الصورة في storage/app/public/qrcodes/<id>.png
    $fileName = 'qrcodes/' . $guest->id . '.png';
    \Storage::disk('public')->put($fileName, $qrImage);

    // 6) حدّث حقل المسار في الداتابيز
    $guest->update(['qr_code_path' => $fileName]);

    // 7) أظهر صفحة النجاح
    return view('gest.qr-success', compact('guest'));
    }

    public function show(Guest $guest)
    {
        $guest->load('sessions.orders.menuItem');
        return view('guests.show', compact('guest'));
    }

    public function edit(Guest $guest)
    {
        return view('guests.edit', compact('guest'));
    }

    public function update(Request $request, Guest $guest)
    {
        $guest->update($request->all());
        return redirect()->route('guests.index')->with('success', 'Guest updated successfully');
    }

    public function destroy(Guest $guest)
    {
        $guest->delete();
        return redirect()->route('guests.index')->with('success', 'Guest deleted successfully');
    }

    // New methods for login form and processing login
    public function showLoginForm()
    {
        return view('gest.login');
    }

    public function login(Request $request)
    {
    
    
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $guest = Guest::where('email', $request->email)->first();
    // dd($guest);

    if (!$guest || !\Hash::check($request->password, $guest->password)) {
        return back()->withErrors(['email' => 'Email or password is incorrect'])->withInput();
    }

    session(['guest_id' => $guest->id]);

    return redirect()->route('profile.user', ['guest' => $guest]);

    
    
    }


    public function overviewSnapshot(Guest $guest)
    {
    $currentSession = $guest->sessions()->latest()->first();

    if (!$currentSession || $currentSession->check_out) {
        return response()->json([
            'active' => false
        ]);
    }

    $liveBill = app(\App\Http\Controllers\SessionController::class)->calculateSessionBill($currentSession);
    $snapshot = \App\Presenters\GuestSessionPresenter::live($currentSession, $liveBill);

    return response()->json([
        'active' => true,
        'session' => $snapshot,
    ]);
    }

    public function currentOrdersPartial(Guest $guest)
{
    $orders = $guest->orders()
    ->whereIn('status', ['Pending', 'InProgress', 'Done'])
    ->latest()
    ->get()
    ->map(function ($order) {
        return [
            'name'   => $order->menuItem?->name ?? 'Unknown',
            'time'   => $order->created_at?->diffForHumans() ?? '-',
            'status' => $order->status,
            'by'     => $order->staff?->name ?? 'N/A',
        ];
    });

return view('gest.partials.current_orders', compact('orders'));

}






}
