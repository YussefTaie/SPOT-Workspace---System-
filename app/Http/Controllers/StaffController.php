<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::all();
        return view('staff.index', compact('staff'));
    }

    public function create()
    {
        return view('staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:staff',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);

        Staff::create($data);
        return redirect()->route('staff.index')->with('success', 'Staff added successfully');
    }

    public function show(Staff $staff)
    {
        return view('staff.show', compact('staff'));
    }

    public function edit(Staff $staff)
    {
        return view('staff.edit', compact('staff'));
    }

    public function update(Request $request, Staff $staff)
    {
        $staff->update($request->all());
        return redirect()->route('staff.index')->with('success', 'Staff updated successfully');
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();
        return redirect()->route('staff.index')->with('success', 'Staff deleted successfully');
    }
}
