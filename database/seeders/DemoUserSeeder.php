<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => Hash::make('Admin123!')]
        );
        $admin->assignRole('admin');

        $instructor = User::firstOrCreate(
            ['email' => 'instructor@example.com'],
            ['name' => 'Instructor', 'password' => Hash::make('Instructor123!')]
        );
        $instructor->assignRole('instructor');
    }
}