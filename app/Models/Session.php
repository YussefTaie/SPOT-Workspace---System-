<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id',
        'table_number',
        'check_in',
        'check_out',
        'duration_minutes',
        'rate_per_hour',
        'bill_amount',
        'people_count',
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
