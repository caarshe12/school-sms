@extends('layouts.app')

@section('title', 'Mark Attendance - ' . $class->name . ' - ' . $date)
@section('header', 'Mark Attendance: ' . $class->name . ' ' . $class->section . ' (' . $date . ')')

@section('content')
    <form action="{{ route('attendance.store') }}" method="POST">
        @csrf
        <input type="hidden" name="school_class_id" value="{{ $class->id }}">
        <input type="hidden" name="date" value="{{ $date }}">

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Present</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Absent</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Excused</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($class->students as $student)
                        @php
                            $status = $attendances->has($student->id) ? $attendances[$student->id]->status : 'present';
                        @endphp
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <input type="radio" name="attendances[{{ $student->id }}]" value="present" {{ $status == 'present' ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <input type="radio" name="attendances[{{ $student->id }}]" value="absent" {{ $status == 'absent' ? 'checked' : '' }} class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <input type="radio" name="attendances[{{ $student->id }}]" value="excused" {{ $status == 'excused' ? 'checked' : '' }} class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No students found in this class. <a href="{{ route('students.create') }}" class="text-indigo-600 hover:text-indigo-900">Add a student</a> first.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('attendance.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">
                Cancel
            </a>
            <button type="submit" class="bg-green-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Save Attendance
            </button>
        </div>
    </form>
@endsection
