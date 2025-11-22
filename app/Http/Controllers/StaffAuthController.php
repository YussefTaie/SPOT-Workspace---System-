<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffAuthController extends Controller
{
    // Show login form (if لديك already, خليه يظهر نفس الفيو)
    public function showLogin()
    {
        return view('staff_login'); // عدل المسار لو الفيو باسم تاني
    }

    // Login handler
    public function login(Request $request)
{
    $request->validate([
        'email' => ['required','email'],
        'password' => ['required'],
    ]);

    $credentials = $request->only('email', 'password');
    $remember = $request->boolean('remember', false);

    $staff = \App\Models\Staff::where('email', $credentials['email'])->first();

    if (!$staff) {
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    // تحديد الـ guard بناءً على الدور
    $guard = null;
    if (strtolower($staff->role) === 'admin') {
        $guard = 'admin';
    } elseif (strtolower($staff->role) === 'barista') {
        $guard = 'barista';
    }

    if ($guard && Auth::guard($guard)->attempt($credentials, $remember)) {
        $request->session()->regenerate();

        if ($guard === 'barista') {
            return redirect()->route('barista.dashboard');
        }

        return redirect()->route('admin.dashboard');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->withInput($request->only('email'));
}


    // Logout
    public function logout(Request $request)
{
    $guard = null;
    $staff = Auth::guard('admin')->user() ?? Auth::guard('barista')->user();

    if ($staff) {
        $guard = strtolower($staff->role);
    }

    if ($guard) {
        Auth::guard($guard)->logout();
    }

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('staff.login');
}

}
