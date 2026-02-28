<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'guest_id',
        'enrollment_type',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'payment_status',
    ];

    /**
     * Get the course that this enrollment belongs to.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the guest who enrolled.
     */
    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
}
