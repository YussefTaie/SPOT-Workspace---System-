<?php

namespace App\Models;
use Vinkla\Hashids\Facades\Hashids;

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

    public function getRouteKey()
    {
        return Hashids::encode($this->id);
    }

    public function resolveRouteBinding($value, $field = null)
    {
    // لو القيمة رقمية (زي 15)، رجّعها مباشرة
    if (is_numeric($value)) {
        return $this->where('id', $value)->firstOrFail();
    }

    // لو القيمة مش رقمية → نفترض إنها Hashid
    $decoded = \Vinkla\Hashids\Facades\Hashids::decode($value);

    if (count($decoded) === 0) {
        abort(404);
    }

    return $this->where('id', $decoded[0])->firstOrFail();
    }

    public function orders()
    {
         return $this->hasManyThrough(Order::class, Session::class, 'guest_id', 'session_id');
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}
