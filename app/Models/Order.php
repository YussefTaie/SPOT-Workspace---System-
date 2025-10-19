<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'menu_item_id',
        'quantity',
        'status',
        'ordered_by'
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
