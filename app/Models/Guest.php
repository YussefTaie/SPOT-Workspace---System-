<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullname',
        'email',
        'phone',
        'college',
        'university',
        'password',
        'registered_at',
        'visits_count',
        'total_time',
        'total_expenses',
        'qr_code_path',
    ];

    public function orders()
    {
         return $this->hasManyThrough(Order::class, Session::class, 'guest_id', 'session_id');
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}
