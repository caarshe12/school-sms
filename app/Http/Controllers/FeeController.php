<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::withCount('students')->get();
        return view('fees.index', compact('classes'));
    }

    public function create()
    {
        $classes = SchoolClass::select('id', 'name', 'section')->get();
        return view('fees.create', compact('classes'));
    }
    
    public function show(Request $request, $id)
    {
         $class = SchoolClass::with('students')->findOrFail($id);
         $period = $request->period ?? date('Y-m');
         $type = $request->type; // Allow null
         
         // Get available types for this class to populate dropdown
         $feeTypes = Fee::where('school_class_id', $id)
                        ->select('type')
                        ->distinct()
                        ->pluck('type');
        
         $students = $class->students;

         // Get fees for this class/period
         $query = Fee::where('school_class_id', $id)
                    ->where('period', $period);

         if ($type) {
             $query->where('type', $type);
         }

         $fees = $query->get()->keyBy('student_id');

         return view('fees.show', compact('class', 'students', 'fees', 'period', 'type', 'feeTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'type' => 'required|string', // Tuition, Transport, etc.
            'amount' => 'required|numeric|min:0',
            'period' => 'required|string', // e.g. 2024-03
            'due_date' => 'required|date',
        ]);

        $class = SchoolClass::with('students')->findOrFail($request->school_class_id);
        $count = 0;

        $feeData = [];
        $now = now();

        // Get existing fees for this class/period/type to avoid duplicates
        $existingStudentIds = Fee::where('school_class_id', $class->id)
            ->where('type', $request->type)
            ->where('period', $request->period)
            ->pluck('student_id')
            ->toArray();

        foreach ($class->students as $student) {
            if (!in_array($student->id, $existingStudentIds)) {
                $feeData[] = [
                    'student_id' => $student->id,
                    'school_class_id' => $class->id,
                    'type' => $request->type,
                    'period' => $request->period,
                    'amount' => $request->amount,
                    'paid' => 0,
                    'status' => 'pending',
                    'due_date' => $request->due_date,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $count++;
            }
        }

        if (!empty($feeData)) {
            Fee::insert($feeData);
        }

        return redirect()->route('fees.show', ['fee' => $class->id, 'period' => $request->period, 'type' => $request->type])
                        ->with('success', "$count monthly fee records generated for {$class->name}.");
    }

    public function destroy(Fee $fee)
    {
        $fee->delete();
        return redirect()->route('fees.index')
                        ->with('success', 'Fee deleted successfully.');
    }

    public function pay(Request $request, $feeId)
    {
        $fee = Fee::findOrFail($feeId);
        
        // Check if already paid
        if ($fee->status === 'paid') {
            return back()->with('info', 'Fee is already paid.');
        }

        // Create full payment
        $amountToPay = $fee->amount - $fee->paid;
        
        \App\Models\Payment::create([
            'student_id' => $fee->student_id,
            'fee_id' => $fee->id,
            'amount' => $amountToPay,
            'payment_date' => now(),
            'method' => 'cash', // Default for quick pay
            'reference' => 'Quick Pay',
        ]);

        $fee->paid = $fee->amount;
        $fee->status = 'paid';
        $fee->save();

        return back()->with('success', 'Fee marked as paid.');
    }

    public function unpay(Request $request, $feeId)
    {
        $fee = Fee::findOrFail($feeId);
        
        // Delete all payments associated with this fee
        $fee->payments()->delete();
        
        $fee->paid = 0;
        $fee->status = 'pending';
        $fee->save();

        return back()->with('success', 'Fee marked as unpaid.');
    }
}
