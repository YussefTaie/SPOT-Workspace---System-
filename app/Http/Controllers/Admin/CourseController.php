<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Guest;

class CourseController extends Controller
{
    /**
     * Display all courses with enrollment counts.
     */
    public function index()
    {
        $courses = Course::withCount('enrollments')->get();
        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Store a new course.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'full_price' => 'required|numeric|min:0',
            'session_price' => 'required|numeric|min:0',
        ]);

        $data['created_by'] = auth('admin')->id() ?? auth('staff')->id();

        Course::create($data);

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully');
    }

    /**
     * Display course details with enrolled students.
     */
    public function show(Course $course)
    {
        $query = $course->enrollments()->with('guest');

        // Filter by payment status
        $status = request('status');
        if ($status && in_array($status, ['paid', 'partial', 'unpaid'])) {
            $query->where('payment_status', $status);
        }

        // Search by guest name or phone
        $search = request('search');
        if ($search) {
            $query->whereHas('guest', function ($q) use ($search) {
                $q->where('fullname', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $enrollments = $query->get();
        
        return view('admin.courses.show', compact('course', 'enrollments'));
    }

    /**
     * Add a student to the course.
     */
    public function storeEnrollment(Request $request, Course $course)
    {
        $data = $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'enrollment_type' => 'required|in:full,per_session',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        // Calculate total_amount based on enrollment_type
        $total_amount = $data['enrollment_type'] === 'full' 
            ? $course->full_price 
            : $course->session_price;

        $paid_amount = $data['paid_amount'];
        $remaining_amount = $total_amount - $paid_amount;

        // Determine payment_status
        if ($remaining_amount == 0) {
            $payment_status = 'paid';
        } elseif ($paid_amount > 0 && $remaining_amount > 0) {
            $payment_status = 'partial';
        } else {
            $payment_status = 'unpaid';
        }

        // Check if student is already enrolled
        $existing = CourseEnrollment::where('course_id', $course->id)
            ->where('guest_id', $data['guest_id'])
            ->first();

        if ($existing) {
            return back()->with('error', 'Student is already enrolled in this course');
        }

        CourseEnrollment::create([
            'course_id' => $course->id,
            'guest_id' => $data['guest_id'],
            'enrollment_type' => $data['enrollment_type'],
            'total_amount' => $total_amount,
            'paid_amount' => $paid_amount,
            'remaining_amount' => $remaining_amount,
            'payment_status' => $payment_status,
        ]);

        return back()->with('success', 'Student enrolled successfully');
    }

    /**
     * Update enrollment payment.
     */
    public function updateEnrollment(Request $request, CourseEnrollment $courseEnrollment)
    {
        $data = $request->validate([
            'paid_amount' => 'required|numeric|min:0',
        ]);

        $paid_amount = $data['paid_amount'];
        $remaining_amount = $courseEnrollment->total_amount - $paid_amount;

        // Determine payment_status
        if ($remaining_amount == 0) {
            $payment_status = 'paid';
        } elseif ($paid_amount > 0 && $remaining_amount > 0) {
            $payment_status = 'partial';
        } else {
            $payment_status = 'unpaid';
        }

        $courseEnrollment->update([
            'paid_amount' => $paid_amount,
            'remaining_amount' => $remaining_amount,
            'payment_status' => $payment_status,
        ]);

        return back()->with('success', 'Payment updated successfully');
    }

    /**
     * Remove a student from the course.
     */
    public function destroyEnrollment(CourseEnrollment $courseEnrollment)
    {
        $courseEnrollment->delete();
        return back()->with('success', 'Student removed from course');
    }

    /**
     * Update course information.
     */
    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'full_price' => 'required|numeric|min:0',
            'session_price' => 'required|numeric|min:0',
        ]);

        $course->update($data);

        return back()->with('success', 'Course updated successfully');
    }

    /**
     * Delete a course.
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully');
    }
}
