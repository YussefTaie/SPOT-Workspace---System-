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
        // Validate input
        $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember', false);

        // Attempt to login using the 'staff' guard
        if (Auth::guard('staff')->attempt($credentials, $remember)) {

            // IMPORTANT: regenerate session to prevent fixation + ensure CSRF token is fresh
            $request->session()->regenerate();

            // Optional: if you want role-based redirect (admin vs barista)
            $staff = Auth::guard('staff')->user();

            if ($staff && strtolower($staff->role ?? '') === 'barista') {
                return redirect()->route('barista.dashboard');
            }

            // default after staff login
            return redirect()->route('admin.dashboard');
        }

        // on failure
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::guard('staff')->logout();

        // invalidate session and regenerate token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('staff.login');
    }
}
