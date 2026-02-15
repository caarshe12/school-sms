<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Staff;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = SchoolClass::with(['teacher', 'students'])->latest()->get();
        return view('classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = Staff::select('id', 'first_name', 'last_name')->get(); // Optimized selection
        return view('classes.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'section' => 'nullable',
            'teacher_id' => 'nullable|exists:staff,id',
        ]);

        SchoolClass::create($request->all());

        return redirect()->route('classes.index')
                        ->with('success', 'Class created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolClass $schoolClass)
    {
        $schoolClass->load(['teacher', 'students']);
        return view('classes.show', compact('schoolClass'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolClass $schoolClass)
    {
        $teachers = Staff::all();
        return view('classes.edit', compact('schoolClass', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchoolClass $schoolClass)
    {
        $request->validate([
            'name' => 'required',
            'section' => 'nullable',
            'teacher_id' => 'nullable|exists:staff,id',
        ]);

        $schoolClass->update($request->all());

        return redirect()->route('classes.index')
                        ->with('success', 'Class updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolClass $schoolClass)
    {
        $schoolClass->delete();

        return redirect()->route('classes.index')
                        ->with('success', 'Class deleted successfully');
    }
}
