<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FacultyMember;
use App\Models\User;
use App\Models\Department;

class FacultyMemberSeeder extends Seeder
{
    public function run(): void
    {
        $instructorUser = User::where('email', 'instructor@example.com')->first();
        $dept = Department::firstOrCreate(['code' => 'GEN'], ['name' => 'General']);
        if ($instructorUser && $dept) {
            FacultyMember::firstOrCreate(
                ['user_id' => $instructorUser->id],
                [
                    'name' => 'John Instructor',
                    'email' => 'instructor@example.com',
                    'department_id' => $dept->id,
                    'employee_code' => 'EMP-0001'
                ]
            );
        }
    }
}