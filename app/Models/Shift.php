<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $table = 'shifts';

    protected $fillable = [
        'started_at',
        'ended_at',
        'total_amount',
        'opened_by',
        'closed_by',
        'shift_number',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Sessions that belong to this shift
     */
    public function sessions()
    {
        return $this->hasMany(\App\Models\Session::class);
    }

    /**
     * Scope: get active (open) shift
     */
    public function scopeActive($query)
    {
        return $query->whereNull('ended_at');
    }
}
