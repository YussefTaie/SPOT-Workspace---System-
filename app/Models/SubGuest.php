<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubGuest extends Model
{
    use HasFactory;

    protected $table = 'sub_guests';

    protected $fillable = [
        'session_id',
        'name',
        'joined_at',
        'left_at',
    ];

    protected $dates = [
        'joined_at',
        'left_at',
    ];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }
    
    public function scopeActive($query)
    {
        return $query->whereNull('left_at');
    }
}
