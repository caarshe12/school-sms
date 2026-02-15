@extends('layouts.app')

@section('title', 'Fees Management')
@section('header', 'Select Class')

@section('content')
    <div class="mb-6 flex justify-between">
        <h2 class="text-xl font-semibold text-gray-800">Classes</h2>
        <a href="{{ route('fees.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
            Generate Fees
        </a>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($classes as $class)
            <a href="{{ route('fees.show', $class->id) }}" class="block p-6 bg-white rounded-lg border border-gray-200 shadow-md hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $class->name }} {{ $class->section }}</h5>
                <p class="font-normal text-gray-700 dark:text-gray-400">Total Students: {{ $class->students_count }}</p>
                <div class="mt-4 flex items-center text-indigo-600">
                    <span>Manage Fees</span>
                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </a>
        @endforeach
    </div>
@endsection
