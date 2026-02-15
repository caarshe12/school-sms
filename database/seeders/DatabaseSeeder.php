<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SchoolClass;
use App\Models\User;
use App\Models\Student;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Classes
        $classes = [
            ['name' => 'Form 1', 'section' => 'A'],
            ['name' => 'Form 1', 'section' => 'B'],
            ['name' => 'Form 2', 'section' => 'A'],
            ['name' => 'Form 2', 'section' => 'B'],
            ['name' => 'Form 3', 'section' => 'A'],
            ['name' => 'Form 3', 'section' => 'B'],
            ['name' => 'Form 4', 'section' => 'A'],
            ['name' => 'Form 4', 'section' => 'B'],
        ];

        foreach ($classes as $classData) {
            $class = SchoolClass::create($classData);
            
            // 2. Create Students for each Class
            $students = Student::factory(5)->create([
                'school_class_id' => $class->id,
                'enrollment_date' => now(),
            ]);

            // Create User accounts for these students
            foreach ($students as $index => $student) {
                User::create([
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'email' => $student->email,
                    'username' => 'STU-' . $class->name . '-' . str_pad($student->id, 3, '0', STR_PAD_LEFT), // STU-Form 1-001
                    'password' => 'password', // Plain text, handled by 'hashed' cast
                    'role' => 'student',
                ]);
            }
        }

        // 4. Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@school.com',
            'username' => 'ADMIN',
            'password' => 'password',
            'role' => 'admin',
        ]);

        // 5. Create Teacher User (Manual)
        User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@school.com',
            'username' => 'TEA-001',
            'password' => 'password',
            'role' => 'teacher',
        ]);

        // 6. Create Staff and their Users
        $staffMembers = \App\Models\Staff::factory(5)->create();

        foreach ($staffMembers as $index => $staff) {
            // Check if user exists
            if (!User::where('email', $staff->email)->exists()) {
                User::create([
                    'name' => $staff->first_name . ' ' . $staff->last_name,
                    'email' => $staff->email,
                    'username' => 'TEA-' . str_pad($index + 2, 3, '0', STR_PAD_LEFT), // TEA-002, TEA-003...
                    'password' => 'password',
                    'role' => 'teacher',
                ]);
            }
        }
    }
}
