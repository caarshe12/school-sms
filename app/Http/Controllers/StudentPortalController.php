<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\ExamRecord;

class StudentPortalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Find the student linked to this user
        $student = Student::where('email', $user->email)->with('schoolClass')->first();

        if (!$student) {
            return view('student.no-record');
        }

        // Fetch exam records
        $allRecords = ExamRecord::where('student_id', $student->id)
                                ->with(['exam', 'exam.schoolClass'])
                                ->get();

        // Group by Class Name (e.g., "Class 1", "Class 2")
        // This groups all sections together for the same year/level
        $recordsByClass = $allRecords->groupBy(function($record) {
            return $record->exam->schoolClass->name;
        })->sortKeys(); // Sort by class name (e.g. Class 1, Class 2...)

        $yearlyResults = [];

        foreach ($recordsByClass as $className => $records) {
             $totalMarks = 0;
             $totalMax = 0;
             
             foreach ($records as $record) {
                 $totalMarks += $record->marks;
                 $totalMax += $record->max_marks;
             }
             
             $average = $totalMax > 0 ? round(($totalMarks / $totalMax) * 100, 2) : 0;
             
             $yearlyResults[] = [
                 'class_name' => $className,
                 'records' => $records,
                 'average' => $average
             ];
        }

        // Calculate Overall Average (Cumulative) if still needed, or we can just show it 
        // but the user focused on yearly. Let's keep the overall var but the view will change.
        $totalMarksAll = 0;
        $totalMaxAll = 0;
        foreach($allRecords as $record) {
             $totalMarksAll += $record->marks;
             $totalMaxAll += $record->max_marks;
        }
        $overallAverage = $totalMaxAll > 0 ? round(($totalMarksAll / $totalMaxAll) * 100, 2) : 0;

        return view('student.portal', compact('student', 'yearlyResults', 'overallAverage'));
    }
}
