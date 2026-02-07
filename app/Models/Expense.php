<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $table = 'expenses';

    protected $fillable = [
        'amount',
        'note',
        'expense_date',
        'shift_id',
        'created_by',
    ];

    public $timestamps = false;
}
