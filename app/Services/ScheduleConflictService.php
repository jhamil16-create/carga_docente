<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ScheduleConflictService
{
    /**
     * Checks for overlapping schedules on classroom or faculty for a given window.
     */
    public function hasConflict(?int $classroomId, ?int $facultyMemberId, int $dayOfWeek, string $startTime, string $endTime, ?int $excludeScheduleId = null): bool
    {
        if (!Schema::hasTable('schedules')) {
            return false; // DB not ready; treat as no conflict
        }

        // Group conditions to avoid OR precedence issues and ensure day/overlap apply globally
        return DB::table('schedules')
            ->where('day_of_week', $dayOfWeek)
            ->when($excludeScheduleId, fn($q) => $q->where('id', '!=', $excludeScheduleId))
            ->where(function ($q) use ($classroomId, $facultyMemberId) {
                if ($classroomId) {
                    $q->where('classroom_id', $classroomId);
                }
                if ($facultyMemberId) {
                    $method = $classroomId ? 'orWhere' : 'where';
                    $q->$method('faculty_member_id', $facultyMemberId);
                }
            })
            // Overlap: start < existing_end AND end > existing_start
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            })
            ->exists();
    }
}