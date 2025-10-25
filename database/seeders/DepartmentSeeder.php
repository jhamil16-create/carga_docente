<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        Department::firstOrCreate(['code' => 'GEN'], ['name' => 'General']);
    }
}