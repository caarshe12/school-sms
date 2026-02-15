<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Student Portal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Student Profile Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-bold">{{ $student->first_name }} {{ $student->last_name }}</h3>
                        <p class="text-gray-500">Student ID: {{ $student->student_code }}</p>
                        <p class="text-indigo-600 font-semibold mt-1">{{ $student->schoolClass->name }} {{ $student->schoolClass->section }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-500 uppercase">Overall Average</div>
                        <div class="text-4xl font-bold {{ $overallAverage >= 50 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $overallAverage }}%
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exam Results -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Examination Results</h3>
                </div>
                
                <div class="p-6">
                <div class="p-6">
                    @forelse ($yearlyResults as $yearData)
                        <div class="mb-8 border border-green-200 rounded-lg overflow-hidden">
                            <!-- Class/Year Header -->
                            <div class="bg-green-100 dark:bg-green-900 px-6 py-3 border-b border-green-200 dark:border-green-800 flex justify-between items-center">
                                <div>
                                    <h4 class="text-lg font-bold text-green-900 dark:text-green-100">
                                        Class: {{ $yearData['class_name'] }}
                                    </h4>
                                </div>
                                <div>
                                    <span class="text-sm font-semibold text-green-800 dark:text-green-200 uppercase tracking-wider mr-2">Average:</span>
                                    <span class="text-lg font-bold text-green-900 dark:text-green-100">{{ $yearData['average'] }}%</span>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-2 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider border-r">Code</th>
                                            <th scope="col" class="px-6 py-2 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider border-r">Subject</th>
                                            <th scope="col" class="px-6 py-2 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider border-r">Marks</th>
                                            <th scope="col" class="px-6 py-2 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($yearData['records'] as $record)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-6 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 border-r">
                                                {{-- Assuming exam name or ID can be used as code, or just a counter --}}
                                                {{ $record->exam->name }} 
                                            </td>
                                            <td class="px-6 py-2 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 border-r">
                                                {{ $record->exam->subject }}
                                            </td>
                                            <td class="px-6 py-2 whitespace-nowrap text-sm text-center font-bold text-gray-900 dark:text-gray-100 border-r">
                                                {{ $record->marks }}
                                            </td>
                                            <td class="px-6 py-2 whitespace-nowrap text-center text-sm">
                                                @php
                                                    $percentage = ($record->marks / $record->max_marks) * 100;
                                                    $grade = 'F';
                                                    if ($percentage >= 90) $grade = 'A';
                                                    elseif ($percentage >= 80) $grade = 'B';
                                                    elseif ($percentage >= 70) $grade = 'C';
                                                    elseif ($percentage >= 60) $grade = 'D';
                                                    elseif ($percentage >= 50) $grade = 'E';
                                                @endphp
                                                <span class="font-bold {{ $grade === 'F' ? 'text-red-600' : 'text-gray-700 dark:text-gray-300' }}">
                                                    {{ $grade }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No exam records found.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
