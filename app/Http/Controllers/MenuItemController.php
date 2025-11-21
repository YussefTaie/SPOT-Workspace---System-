<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Guest;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    // View for barista (if you use it)
    public function menu()
    {
        $menuItems = MenuItem::where('available', true)->orderBy('name')->get();
        return view('barista.menu', compact('menuItems'));
    }

    // Public menu index (uses resources/views/menu/index.blade.php)
    public function index(Request $request)
    {
        $query = MenuItem::query();

        // optional search
        if ($q = $request->input('q')) {
            $query->where('name', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%");
        }

        // optional category filter
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        $menuItems = $query->where('available', true)->orderBy('created_at', 'desc')->get();
        return view('admin.menu.index', compact('menuItems'));
    }

    // باقي الدوال create/store/show/edit/update/destroy زي ما عندك...

    public function guestMenu(Guest $guest)
{
    // نجيب المنيو كلها (أو الفئات اللي متاحة فقط)
    $menuItems = MenuItem::where('available', true)->orderBy('name')->get();

    // نرجع الصفحة مع بيانات الجيست
    return view('barista.menu', compact('menuItems', 'guest'));
}
}
