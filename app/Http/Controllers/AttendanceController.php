<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::all();
        return view('attendance.index', compact('classes'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'date' => 'required|date',
        ]);

        $class = SchoolClass::with(['students' => function($query) {
            $query->orderBy('first_name');
        }])->findOrFail($request->school_class_id);

        $date = $request->date;
        
        // Check if attendance already exists for this date to pre-fill
        $attendances = StudentAttendance::where('school_class_id', $class->id)
                                        ->where('date', $date)
                                        ->get()
                                        ->keyBy('student_id');

        return view('attendance.create', compact('class', 'date', 'attendances'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*' => 'required|in:present,absent,excused',
        ]);

        $classId = $request->school_class_id;
        $date = $request->date;

        foreach ($request->attendances as $studentId => $status) {
            StudentAttendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'date' => $date,
                ],
                [
                    'school_class_id' => $classId,
                    'status' => $status,
                ]
            );
        }

        return redirect()->route('attendance.index')
                        ->with('success', 'Attendance recorded successfully for ' . $date);
    }

    public function report(Request $request)
    {
        $classes = SchoolClass::all();
        $selectedClass = null;
        $selectedMonth = $request->month ?? date('Y-m');
        $attendanceData = [];
        $daysInMonth = [];

        if ($request->school_class_id) {
            $selectedClass = SchoolClass::with(['students' => function($query) {
                $query->orderBy('first_name');
            }])->findOrFail($request->school_class_id);

            $year = date('Y', strtotime($selectedMonth));
            $month = date('m', strtotime($selectedMonth));
            $daysCount = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            for ($i = 1; $i <= $daysCount; $i++) {
                $daysInMonth[] = $i;
            }

            $attendances = StudentAttendance::where('school_class_id', $selectedClass->id)
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->get()
                ->groupBy('student_id');

            foreach ($selectedClass->students as $student) {
                $attendanceData[$student->id] = [];
                $studentAttendances = $attendances->get($student->id, collect());

                for ($i = 1; $i <= $daysCount; $i++) {
                    $date = sprintf('%s-%s-%02d', $year, $month, $i);
                    $record = $studentAttendances->firstWhere('date', $date);
                    $attendanceData[$student->id][$i] = $record ? $record->status : '-';
                }
            }
        }

        return view('attendance.report', compact('classes', 'selectedClass', 'selectedMonth', 'attendanceData', 'daysInMonth'));
    }
}
