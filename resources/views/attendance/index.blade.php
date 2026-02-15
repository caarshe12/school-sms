@extends('layouts.app')

@section('title', 'Take Attendance - School Management')
@section('header', 'Take Attendance')

@section('content')
    <div class=" bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6 mb-4 flex justify-between items-center">
             <h3 class="text-lg leading-6 font-medium text-gray-900">Take Daily Attendance</h3>
             <a href="{{ route('attendance.report') }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">View Monthly Report &rarr;</a>
        </div>

        <div class="px-4 pb-5 sm:px-6">
            <form action="{{ route('attendance.create') }}" method="GET">
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <label for="school_class_id" class="block text-sm font-medium text-gray-700">Class</label>
                        <select id="school_class_id" name="school_class_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select a Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }} {{ $class->section }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border p-2">
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Proceed to Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
