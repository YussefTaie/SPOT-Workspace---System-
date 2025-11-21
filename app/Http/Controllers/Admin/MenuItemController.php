<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuItem;

class MenuItemController extends Controller
{
    // Show list (admin view)
    public function index()
    {
        $items = MenuItem::orderBy('created_at','desc')->get();
        return view('admin.menu.index', compact('items'));
    }

    // Show create form
    public function create()
    {
        return view('admin.menu.create');
    }

    // Store new item (POST /admin/menu)
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'available' => 'nullable|boolean',
        ]);

        $data['available'] = $request->has('available') ? (bool)$request->available : true;

        MenuItem::create($data);

        return redirect()->route('admin.menu.index')->with('success', 'Item added');
    }

    // Show edit form
    public function edit(MenuItem $menuItem)
    {
        return view('admin.menu.edit', compact('menuItem'));
    }

    // Update item (PUT /admin/menu/{menuItem})
    public function update(Request $request, MenuItem $menuItem)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'available' => 'nullable|boolean',
        ]);

        $data['available'] = $request->has('available') ? (bool)$request->available : false;

        $menuItem->update($data);

        return redirect()->route('admin.menu.index')->with('success', 'Item updated');
    }

    // Delete item (DELETE /admin/menu/{menuItem})
    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();
        return redirect()->route('admin.menu.index')->with('success', 'Item deleted');
    }
}
