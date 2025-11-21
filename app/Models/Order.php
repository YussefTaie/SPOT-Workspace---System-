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
        'ordered_by',
        'unit_price',
        'total_price',
        'staff_id',
        'accepted_at',
        'served_at',
        'bill_amount',
        'takeaway',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'accepted_at',
        'served_at',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'accepted_at' => 'datetime',
        'served_at' => 'datetime',
    ];
    

    // Relations

    public function session()
    {
        return $this->belongsTo(\App\Models\Session::class);
    }

    // Note: your migration doesn't have guest_id, session -> guest relation used in blade
    public function guest()
    {
        return $this->belongsTo(\App\Models\Guest::class);
    }

    public function staff()
    {
        return $this->belongsTo(\App\Models\Staff::class, 'staff_id');
    }

    public function menuItem()
    {
        return $this->belongsTo(\App\Models\MenuItem::class, 'menu_item_id')->withDefault();
    }
}
