<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with('schoolClass')->latest()->paginate(10);
        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = \App\Models\SchoolClass::all();
        return view('students.create', compact('classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'student_code' => 'required|string|unique:students|unique:users,username',
            'email' => 'nullable|email|unique:users,email|unique:students,email',
            'password' => 'required|string|min:4',
            'phone' => 'nullable|string|max:20',
            'school_class_id' => 'required|exists:school_classes,id',
            'enrollment_date' => 'required|date',
        ]);

        // Generate email if not provided (needed for Users table)
        $email = $request->email ?? strtolower($request->student_code) . '@school.local';

        // Create User
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $email,
            'username' => $request->student_code,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        // Create Student
        Student::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'student_code' => $request->student_code,
            'email' => $email, // Link to user via email
            'phone' => $request->phone,
            'school_class_id' => $request->school_class_id,
            'enrollment_date' => $request->enrollment_date,
        ]);

        return redirect()->route('students.index')
                        ->with('success', 'Student created successfully with User account.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $classes = \App\Models\SchoolClass::all();
        return view('students.edit', compact('student', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'student_code' => ['required', 'string', Rule::unique('students')->ignore($student->id), Rule::unique('users', 'username')->ignore($student->email, 'email')],
            'email' => ['nullable', 'email', Rule::unique('students')->ignore($student->id), Rule::unique('users')->ignore($student->email, 'email')],
            'password' => 'nullable|string|min:4',
            'phone' => 'nullable|string|max:20',
            'school_class_id' => 'required|exists:school_classes,id',
            'enrollment_date' => 'required|date',
        ]);

        // Find associated user
        $user = User::where('email', $student->email)->first();

        $email = $request->email ?? $student->email;
        if (!$email && $request->student_code) {
             $email = strtolower($request->student_code) . '@school.local';
        }

        // Update User if exists
        if ($user) {
            $userData = [
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $email,
                'username' => $request->student_code,
            ];
            
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);
        }

        // Update Student
        $student->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'student_code' => $request->student_code,
            'email' => $email,
            'phone' => $request->phone,
            'school_class_id' => $request->school_class_id,
            'enrollment_date' => $request->enrollment_date,
        ]);

        return redirect()->route('students.index')
                        ->with('success', 'Student updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        // Optionally delete the user
        if ($student->email) {
            User::where('email', $student->email)->delete();
        }
        
        $student->delete();

        return redirect()->route('students.index')
                        ->with('success', 'Student deleted successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load('schoolClass');
        
        // Fetch specific student's exam records
        $records = \App\Models\ExamRecord::where('student_id', $student->id)
            ->with(['exam', 'exam.schoolClass'])
            ->get();
            
        // Group by Class Name for the Transcript View
        $recordsByClass = $records->groupBy(function($record) {
            return $record->exam->schoolClass->name;
        })->sortKeys();

        // Get all exams for the "Add Result" dropdown, grouped by Class
        $allExams = \App\Models\Exam::with('schoolClass')
            ->get()
            ->groupBy(function($exam) {
                return $exam->schoolClass->name . ' ' . $exam->schoolClass->section;
            })->sortKeys();

        return view('students.show', compact('student', 'recordsByClass', 'allExams'));
    }

    /**
     * Store or update an exam result for a student manually.
     */
    public function storeExamResult(Request $request, Student $student)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'marks' => 'required|numeric|min:0|max:100',
        ]);

        \App\Models\ExamRecord::updateOrCreate(
            ['exam_id' => $request->exam_id, 'student_id' => $student->id],
            ['marks' => $request->marks, 'max_marks' => 100] // Defaulting max to 100
        );

        return back()->with('success', 'Exam result updated successfully.');
    }
}
