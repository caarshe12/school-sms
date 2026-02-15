@extends('layouts.app')

@section('title', $schoolClass->name . ' ' . $schoolClass->section . ' - Students')
@section('header', $schoolClass->name . ' ' . ($schoolClass->section ? 'Section ' . $schoolClass->section : ''))

@section('content')
    <div class="mb-6">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Class Details
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Teacher: 
                    @if($schoolClass->teacher)
                        <span class="font-semibold">{{ $schoolClass->teacher->first_name }} {{ $schoolClass->teacher->last_name }}</span>
                    @else
                        <span class="text-red-500">Unassigned</span>
                    @endif
                </p>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Total Students: {{ $schoolClass->students->count() }}
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Student List
            </h3>
        </div>
        
        @if($schoolClass->students->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrollment Date</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($schoolClass->students as $student)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $student->phone ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $student->enrollment_date }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('students.edit', $student->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
            <div class="p-6 text-center text-gray-500">
                No students assigned to this class yet.
            </div>
        @endif
    </div>

    <div class="mt-6">
        <a href="{{ route('classes.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
            &larr; Back to All Classes
        </a>
    </div>
@endsection
