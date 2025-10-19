<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Session;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('session.guest', 'menuItem')->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $sessions = Session::all();
        $menuItems = MenuItem::where('available', true)->get();
        return view('orders.create', compact('sessions', 'menuItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:sessions,id',
            'menu_item_id' => 'required|exists:menu_items,id',
            'quantity' => 'required|integer|min:1',
            'status' => 'required'
        ]);

        Order::create($request->all());
        return redirect()->route('orders.index')->with('success', 'Order created successfully');
    }

    public function show(Order $order)
    {
        $order->load('session.guest', 'menuItem');
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $sessions = Session::all();
        $menuItems = MenuItem::all();
        return view('orders.edit', compact('order', 'sessions', 'menuItems'));
    }

    public function update(Request $request, Order $order)
    {
        $order->update($request->all());
        return redirect()->route('orders.index')->with('success', 'Order updated successfully');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully');
    }
}
