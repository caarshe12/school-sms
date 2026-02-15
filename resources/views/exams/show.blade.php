@extends('layouts.app')

@section('title', $exam->name . ' - Marks')
@section('header', 'Marks for ' . $exam->name . ' (' . $exam->subject . ')')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Class</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $exam->schoolClass->name }} {{ $exam->schoolClass->section }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Date</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $exam->date }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Student Marks</h3>
        </div>
        
        <form action="{{ route('exams.marks.updateAll', $exam->id) }}" method="POST">
            @csrf
            
            <ul class="divide-y divide-gray-200">
                @foreach($exam->schoolClass->students as $student)
                    @php
                        $record = $records->get($student->id);
                        $marks = $record ? $record->marks : '';
                    @endphp
                    <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-medium text-indigo-600 truncate">
                                {{ $student->first_name }} {{ $student->last_name }}
                            </div>
                            <div class="ml-2 flex-shrink-0 flex items-center">
                                <label for="marks_{{ $student->id }}" class="sr-only">Marks</label>
                                <input type="number" step="0.01" 
                                       name="marks[{{ $student->id }}]" 
                                       id="marks_{{ $student->id }}"
                                       value="{{ $marks }}" 
                                       placeholder="Marks" 
                                       class="w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-1">
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Save All Marks
                </button>
            </div>
        </form>
    </div>
@endsection
