<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['student', 'fee'])->latest()->paginate(20);
        return view('payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        // Pre-select student and fee if passed in query params (e.g. from Fee Index "Pay" button)
        $preSelectedDetails = [
            'student_id' => $request->student_id,
            'fee_id' => $request->fee_id,
        ];
        
        $students = Student::orderBy('first_name')->get();
        // If a student is selected, primarily show their UNPAID fees
        $fees = Fee::where('status', '!=', 'paid')->latest()->get(); 

        return view('payments.create', compact('students', 'fees', 'preSelectedDetails'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'fee_id' => 'nullable|exists:fees,id', // Can be null for general payments, but better to link
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'method' => 'required|string',
        ]);

        // Create Payment
        $payment = Payment::create($request->all());

        // Update Fee Status if linked
        if ($request->fee_id) {
            $fee = Fee::findOrFail($request->fee_id);
            $fee->paid += $request->amount;
            
            if ($fee->paid >= $fee->amount) {
                $fee->status = 'paid';
            } elseif ($fee->paid > 0) {
                $fee->status = 'partial';
            }
            $fee->save();
        }

        return redirect()->route('fees.index')
                        ->with('success', 'Payment recorded successfully. Fee status updated.');
    }
}
