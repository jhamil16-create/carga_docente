<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\FacultyMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AttendanceController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('attendances')) {
            return view('attendance.index', ['items' => collect(), 'dbReady' => false]);
        }
        $items = Attendance::query()->latest()->paginate(15);
        return view('attendance.index', compact('items') + ['dbReady' => true]);
    }

    public function create()
    {
        $schedules = Schema::hasTable('schedules') ? Schedule::orderBy('day_of_week')->orderBy('start_time')->get() : collect();
        $faculty = Schema::hasTable('faculty_members') ? FacultyMember::orderBy('name')->get() : collect();
        return view('attendance.create', compact('schedules','faculty'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'faculty_member_id' => ['required','integer'],
            'schedule_id' => ['required','integer'],
            'date' => ['required','date'],
            'status' => ['required','in:present,absent,late'],
        ]);

        if (!Schema::hasTable('attendances')) {
            return back()->with('status', 'DB not ready. Migration pending; request validated.');
        }

        try {
            Attendance::create($data);
        } catch (\Throwable $e) {
            return back()->withErrors(['db' => 'Could not record attendance. Ensure IDs exist.'])->withInput();
        }

        return redirect()->route('attendance.index')->with('status', 'Attendance recorded');
    }
}