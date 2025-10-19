<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;

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




}
