<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamRecord;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exams = Exam::with('schoolClass')->latest()->paginate(20);
        return view('exams.index', compact('exams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = SchoolClass::select('id', 'name', 'section')->get();
        return view('exams.create', compact('classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'date' => 'required|date',
            'school_class_id' => 'required|exists:school_classes,id',
        ]);

        $exam = Exam::create($validated);

        return redirect()->route('exams.show', $exam)
                         ->with('success', 'Exam created successfully. Now enter marks.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Exam $exam)
    {
        $exam->load('schoolClass.students');
        
        // Load existing records keyed by student_id for easy lookup
        $records = $exam->records->keyBy('student_id');
        
        return view('exams.show', compact('exam', 'records'));
    }

    /**
     * Update the marks for a specific student in an exam.
     */
    public function updateMark(Request $request, Exam $exam)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'marks' => 'required|numeric|min:0|max:100', // Assuming 100 is max
        ]);

        ExamRecord::updateOrCreate(
            ['exam_id' => $exam->id, 'student_id' => $request->student_id],
            ['marks' => $request->marks, 'max_marks' => 100] // Default max marks to 100 for now or fetch from exam if available
        );

        return back()->with('success', 'Marks updated.');
    }

    /**
     * Update marks for multiple students at once.
     */
    public function updateAllMarks(Request $request, Exam $exam)
    {
        $request->validate([
            'marks' => 'required|array',
            'marks.*' => 'nullable|numeric|min:0|max:100',
        ]);

        foreach ($request->marks as $studentId => $mark) {
            if ($mark !== null) {
                ExamRecord::updateOrCreate(
                    ['exam_id' => $exam->id, 'student_id' => $studentId],
                    ['marks' => $mark]
                );
            }
        }

        return back()->with('success', 'All marks updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->route('exams.index')->with('success', 'Exam deleted successfully.');
    }
}
