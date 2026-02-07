<?php

namespace App\Services;
use Illuminate\Support\Facades\Auth;

use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ShiftService
{
    /**
     * Get active shift for today.
     * If an old open shift exists from previous day, close it and open a new one.
     */
    public function getOrCreateTodayShift(): Shift
    {
        return DB::transaction(function () {

            $now = Carbon::now();

            $openShifts = Shift::whereNull('ended_at')
                ->orderByDesc('started_at')
                ->get();
            $openShift = $openShifts->first();

            // اقفل أي شيفتات مفتوحة زيادة (Safety Net)
            $openShifts->slice(1)->each(function ($shift) {
                $shift->update([
                    'ended_at' => $shift->started_at->copy()->endOfDay(),
                ]);
            });

            if ($openShift) {

                // لو الشيفت المفتوح بتاع يوم فات
                if ($openShift->started_at->toDateString() !== $now->toDateString()) {

                    // اقفله على آخر ثانية في يومه
                    $openShift->update([
                        'ended_at' => $openShift->started_at->copy()->endOfDay(),
                    ]);

                    // افتح شيفت جديد
                    return Shift::create([
                        'started_at'   => $now,
                        'opened_by'    => $this->resolveOpenedBy(),
                        'shift_number' => $this->nextShiftNumber($now),

                    ]);
                }

                // الشيفت الحالي مظبوط
                return $openShift;
            }

            // مفيش شيفت مفتوح
            return Shift::create([
                'started_at'   => $now,
                'opened_by'    => $this->resolveOpenedBy(),
                'shift_number' => $this->nextShiftNumber($now),

            ]);

        });
    }




    /**
     * Force close current shift and open a new one.
     */
    public function forceChangeShift(): Shift
    {
        return DB::transaction(function () {

            $now = Carbon::now();

            $openShift = Shift::whereNull('ended_at')
                ->orderByDesc('started_at')
                ->first();

            if ($openShift) {
                $openShift->update([
                    'ended_at' => $now,
                ]);
            }

            return Shift::create([
                'started_at' => $now,
                'opened_by'  => $this->resolveOpenedBy(),
                'shift_number' => $this->nextShiftNumber($now),

            ]);
        });
    }

    private function resolveOpenedBy(): int
    {
        // Admin logged in
        if (Auth::guard('admin')->check()) {
            return Auth::guard('admin')->id();
        }

        // Fallback system user 
        return 0;
    }
    private function nextShiftNumber(): int
    {
        $now = now();

        return Shift::whereDate('started_at', $now->toDateString())->count() + 1;
    }


}
