<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Guest;

class AdminController extends Controller
{
        public function dashboard()
    {
        // نجيب كل الـ guests اللي عاملين check-in ولسه مخرجوش
        $activeSessions = Session::with('guest')
            ->whereNull('check_out')  
            ->get();

        // نجيب الهيستوري (اللي خرجوا)  
        $historySessions = Session::with('guest')
            ->whereNotNull('check_out')
            ->orderBy('check_out', 'desc')
            ->get()
            ->groupBy(function($session) {
                return \Carbon\Carbon::parse($session->check_out)->format('Y-m-d');
            });

        return view('admin.admin_dashboard', compact('activeSessions', 'historySessions'));
    }

    public function host()
    {
        // نفس الداتا بالظبط
        $activeSessions = Session::with('guest')
            ->whereNull('check_out')
            ->get();
    
        $historySessions = Session::with('guest')
            ->whereNotNull('check_out')
            ->orderBy('check_out', 'desc')
            ->get()
            ->groupBy(function($session) {
                return \Carbon\Carbon::parse($session->check_out)->format('Y-m-d');
            });
    
        // رجّعها للـ host.blade.php
        return view('admin.host', compact('activeSessions', 'historySessions'));
    }

    public function fetchSessionsData()
{
    $activeSessions = Session::with('guest')
        ->whereNull('check_out')
        ->get();

    $historySessions = Session::with('guest')
        ->whereNotNull('check_out')
        ->get();

    return response()->json([
        'active' => $activeSessions,
        'history' => $historySessions,
    ]);
}

public function getDurations()
{
    $activeSessions = \App\Models\Session::with('guest')
        ->whereNull('check_out')
        ->get();

    $durations = [];

    foreach ($activeSessions as $session) {
        $checkIn = \Carbon\Carbon::parse($session->check_in);
        $now = \Carbon\Carbon::now();
        $duration = $checkIn->diff($now);
        $durations[$session->id] = $duration->h . 'h ' . $duration->i . 'm';
    }

    return response()->json($durations);
}


public function searchGuests(Request $request)
{
    $q = trim($request->get('q', ''));

    if (mb_strlen($q) < 3) {
        return response()->json([]);
    }

    $guests = \App\Models\Guest::query()
        ->where(function ($query) use ($q) {
            $query->where('fullname', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%");
        })
        ->limit(10)
        ->get(['id', 'fullname', 'phone']);

    return response()->json($guests);
}

public function requestHoldSession(Request $request)
{
    $guestId = $request->get('guest_id');

    $guest = Guest::findOrFail($guestId);

    $holds = Cache::get('hold_sessions', []);

    // منع التكرار لنفس الجيست
    foreach ($holds as $h) {
        if ((int)$h['guest_id'] === (int)$guestId) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Hold already exists',
            ]);
        }
    }

    $holds[] = [
        'guest_id'     => $guest->id,
        'guest_name'   => $guest->fullname,
        'requested_at' => now()->format('Y-m-d H:i'),
        'requested_by' => auth('admin')->user()->name ?? 'admin',
    ];

    Cache::put('hold_sessions', $holds, now()->addHours(6));

    return response()->json([
        'status' => 'ok',
        'message' => 'Hold session created',
    ]);
}
public function getHoldSessions()
{
    return response()->json(
        Cache::get('hold_sessions', [])
    );
}
public function acceptHoldSession(Request $request)
{
    $guestId = (int) $request->get('guest_id');

    $holds = Cache::get('hold_sessions', []);
    $remaining = [];

    foreach ($holds as $h) {
        if ((int)$h['guest_id'] !== $guestId) {
            $remaining[] = $h;
        }
    }

    Cache::put('hold_sessions', $remaining, now()->addHours(6));

    // ⛔ تأكيد إن مفيش Session مفتوحة
    $active = Session::where('guest_id', $guestId)
        ->whereNull('check_out')
        ->first();

    if ($active) {
        return response()->json([
            'status' => 'error',
            'message' => 'Guest already has an active session',
        ]);
    }

    // ✅ فتح Session حقيقية (نفس QR flow لكن من غير ما نلمسه)
    $session = Session::create([
        'guest_id'     => $guestId,
        'table_number' => 1,
        'check_in'     => now(),
        'rate_per_hour'=> 60,
        'people_count'=> 1,
        'session_type'=> 'regular',
    ]);

    $session->subGuests()->create([
        'name'      => $session->guest->fullname,
        'joined_at'=> now(),
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Session started',
    ]);
}
public function rejectHoldSession(Request $request)
{
    $guestId = (int) $request->get('guest_id');

    $holds = Cache::get('hold_sessions', []);
    $remaining = [];

    foreach ($holds as $h) {
        if ((int)$h['guest_id'] !== $guestId) {
            $remaining[] = $h;
        }
    }

    Cache::put('hold_sessions', $remaining, now()->addHours(6));

    return response()->json([
        'status' => 'ok',
        'message' => 'Hold rejected',
    ]);
}




}
