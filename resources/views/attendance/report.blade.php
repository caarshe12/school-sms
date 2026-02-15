@extends('layouts.app')

@section('title', 'Attendance Report - School Management')
@section('header', 'Attendance Report')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('attendance.report') }}" method="GET">
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <label for="school_class_id" class="block text-sm font-medium text-gray-700">Class</label>
                        <select id="school_class_id" name="school_class_id" onchange="this.form.submit()" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select a Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ (isset($selectedClass) && $selectedClass->id == $class->id) ? 'selected' : '' }}>
                                    {{ $class->name }} {{ $class->section }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="month" class="block text-sm font-medium text-gray-700">Month</label>
                        <input type="month" name="month" id="month" value="{{ $selectedMonth }}" onchange="this.form.submit()" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border p-2">
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($selectedClass)
        <div class="bg-white overflow-x-auto shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">
                            Student Name
                        </th>
                        @foreach($daysInMonth as $day)
                            <th scope="col" class="px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-8">
                                {{ $day }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($selectedClass->students as $student)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap sticky left-0 bg-white z-10 border-r border-gray-200">
                                <div class="text-sm font-medium text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</div>
                            </td>
                            @foreach($daysInMonth as $day)
                                @php
                                    $status = $attendanceData[$student->id][$day];
                                    $colorClass = match($status) {
                                        'present' => 'text-green-600 font-bold',
                                        'absent' => 'text-red-600 font-bold',
                                        'excused' => 'text-blue-600 font-bold',
                                        default => 'text-gray-300',
                                    };
                                    $symbol = match($status) {
                                        'present' => 'P',
                                        'absent' => 'A',
                                        'excused' => 'E',
                                        default => '-',
                                    };
                                @endphp
                                <td class="px-2 py-4 whitespace-nowrap text-center text-xs {{ $colorClass }}">
                                    {{ $symbol }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 px-4 text-sm text-gray-500">
            <span class="mr-4"><span class="text-green-600 font-bold">P</span> = Present</span>
            <span class="mr-4"><span class="text-red-600 font-bold">A</span> = Absent</span>
            <span class="mr-4"><span class="text-blue-600 font-bold">E</span> = Excused</span>
        </div>
    @endif
@endsection
