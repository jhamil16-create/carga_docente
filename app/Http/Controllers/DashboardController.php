<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $dbReady = Schema::hasTable('departments') && Schema::hasTable('subjects') && Schema::hasTable('classrooms') && Schema::hasTable('groups') && Schema::hasTable('schedules') && Schema::hasTable('attendances');
        return view('dashboard.index', compact('dbReady'));
    }
}