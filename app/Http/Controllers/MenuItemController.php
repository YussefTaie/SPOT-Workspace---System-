<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::all();
        return view('menu.index', compact('menuItems'));
    }

    public function create()
    {
        return view('menu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category' => 'required'
        ]);

        MenuItem::create($request->all());
        return redirect()->route('menu.index')->with('success', 'Item added successfully');
    }

    public function show(MenuItem $menuItem)
    {
        return view('menu.show', compact('menuItem'));
    }

    public function edit(MenuItem $menuItem)
    {
        return view('menu.edit', compact('menuItem'));
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $menuItem->update($request->all());
        return redirect()->route('menu.index')->with('success', 'Item updated successfully');
    }

    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();
        return redirect()->route('menu.index')->with('success', 'Item deleted successfully');
    }
}
