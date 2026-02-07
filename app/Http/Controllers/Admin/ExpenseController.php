<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Shift;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'note'   => 'nullable|string|max:255',
        ]);

        $activeShift = Shift::whereNull('ended_at')
            ->orderByDesc('started_at')
            ->first();

        Expense::create([
            'amount'       => $request->amount,
            'note'         => $request->note,
            'expense_date' => now()->toDateString(),
            'shift_id'     => $activeShift?->id,
            'created_by'   => auth('admin')->id(),
        ]);

        return redirect()->back()->with('success', 'Expense added');
    }
}
