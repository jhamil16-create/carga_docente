<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Services\ScheduleConflictService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ScheduleController extends Controller
{
    public function __construct(private ScheduleConflictService $conflictService) {}

    public function index()
    {
        if (!Schema::hasTable('schedules')) {
            return view('schedules.index', ['items' => collect(), 'dbReady' => false]);
        }
        $items = Schedule::query()->latest()->paginate(10);
        return view('schedules.index', compact('items') + ['dbReady' => true]);
    }

    public function create()
    {
        return view('schedules.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'group_id' => ['required','integer'],
            'classroom_id' => ['required','integer'],
            'faculty_member_id' => ['required','integer'],
            'day_of_week' => ['required','integer','between:1,7'],
            'start_time' => ['required','date_format:H:i'],
            'end_time' => ['required','date_format:H:i','after:start_time'],
        ]);

        if ($this->conflictService->hasConflict($data['classroom_id'], $data['faculty_member_id'], $data['day_of_week'], $data['start_time'], $data['end_time'])) {
            return back()->withErrors(['conflict' => 'Schedule conflicts with existing assignment'])->withInput();
        }

        if (!Schema::hasTable('schedules')) {
            return back()->with('status', 'DB not ready. Migration pending; request validated.');
        }

        try {
            Schedule::create($data);
        } catch (\Throwable $e) {
            return back()->withErrors(['db' => 'Could not create schedule. Ensure related IDs exist and constraints pass.'])->withInput();
        }

        return redirect()->route('schedules.index')->with('status', 'Schedule created');
    }
}