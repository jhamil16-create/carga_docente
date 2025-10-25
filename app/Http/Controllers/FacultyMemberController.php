<?php

namespace App\Http\Controllers;

use App\Models\FacultyMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class FacultyMemberController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('faculty_members')) {
            return view('faculty_members.index', ['items' => collect(), 'dbReady' => false]);
        }
        $items = FacultyMember::query()->latest()->paginate(10);
        return view('faculty_members.index', compact('items') + ['dbReady' => true]);
    }

    public function create()
    {
        return view('faculty_members.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255'],
        ]);

        if (!Schema::hasTable('faculty_members')) {
            return back()->with('status', 'DB not ready. Migration pending; request validated.');
        }

        try {
            FacultyMember::create($data);
        } catch (\Throwable $e) {
            return back()->withErrors(['db' => 'Could not create faculty. Check fillable fields or constraints.'])->withInput();
        }

        return redirect()->route('faculty_members.index')->with('status', 'Faculty member created');
    }

    public function edit(FacultyMember $faculty_member)
    {
        return view('faculty_members.edit', compact('faculty_member'));
    }

    public function update(Request $request, FacultyMember $faculty_member)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255'],
        ]);

        try {
            $faculty_member->update($data);
        } catch (\Throwable $e) {
            return back()->withErrors(['db' => 'Could not update faculty.'])->withInput();
        }

        return redirect()->route('faculty_members.index')->with('status', 'Faculty member updated');
    }

    public function destroy(FacultyMember $faculty_member)
    {
        try {
            $faculty_member->delete();
        } catch (\Throwable $e) {
            return back()->withErrors(['db' => 'Could not delete faculty.']);
        }
        return redirect()->route('faculty_members.index')->with('status', 'Faculty member deleted');
    }
}