@extends('layouts.app')

@section('title', 'Generate Monthly Fees')
@section('header', 'Generate Monthly Fees')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('fees.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <label for="school_class_id" class="block text-sm font-medium text-gray-700">Class</label>
                        <select id="school_class_id" name="school_class_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }} {{ $class->section }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Fees will be generated for ALL students in this class.</p>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="period" class="block text-sm font-medium text-gray-700">Month (Period)</label>
                        <input type="month" name="period" id="period" value="{{ date('Y-m') }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border p-2">
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="type" class="block text-sm font-medium text-gray-700">Fee Type</label>
                        <input type="text" name="type" id="type" value="Monthly Tuition" required placeholder="e.g. Tuition" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border p-2">
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="amount" class="block text-sm font-medium text-gray-700">Amount ($)</label>
                        <input type="number" step="0.01" name="amount" id="amount" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border p-2">
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                        <input type="date" name="due_date" id="due_date" value="{{ date('Y-m-t') }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border p-2">
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Generate Invoices
                    </button>
                    <a href="{{ route('fees.index') }}" class="ml-3 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
