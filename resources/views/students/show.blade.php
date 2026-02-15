@extends('layouts.app')

@section('title', 'Student Details - ' . $student->first_name)
@section('header', 'Student Profile & Transcript')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    
    <!-- Student Header -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                    {{ $student->first_name }} {{ $student->last_name }}
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                    Student ID: {{ $student->student_code }} | Current Class: {{ $student->schoolClass->name ?? 'N/A' }}
                </p>
            </div>
            <div>
                <a href="{{ route('students.edit', $student) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Edit Profile
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Left: Academic Transcript -->
        <div class="md:col-span-2 space-y-6">
            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200">Academic Transcript</h3>
            
            @forelse ($recordsByClass as $className => $records)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3 border-b border-gray-200 dark:border-gray-600">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $className }}</h4>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Exam</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subject</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Marks</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Max</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($records as $record)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $record->exam->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $record->exam->subject }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-gray-900 dark:text-gray-100">{{ $record->marks }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-400">{{ $record->max_marks }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow text-center text-gray-500">
                    No academic records found for this student.
                </div>
            @endforelse
        </div>

        <!-- Right: Add New Result -->
        <div class="md:col-span-1">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 sticky top-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Add Exam Result</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Manually add a result for this student for any exam. This allows building the transcript history.
                </p>

                <form action="{{ route('students.exam-results.store', $student) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="exam_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Exam</label>
                        <select name="exam_id" id="exam_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-900 dark:text-gray-100" required>
                            <option value="">-- Select Exam --</option>
                            @foreach ($allExams as $classSection => $exams)
                                <optgroup label="{{ $classSection }}">
                                    @foreach ($exams as $exam)
                                        <option value="{{ $exam->id }}">
                                            {{ $exam->name }} ({{ $exam->subject }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="marks" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Marks Obtained</label>
                        <input type="number" step="0.01" name="marks" id="marks" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-900 dark:text-gray-100" placeholder="e.g. 85" required>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save Result
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
