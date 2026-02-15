@extends('layouts.app')

@section('title', 'System Settings')
@section('header', 'System Configuration')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white px-4 py-5 shadow sm:rounded-lg sm:p-6">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <h3 class="text-lg font-medium leading-6 text-gray-900">General Settings</h3>
                <p class="mt-1 text-sm text-gray-500">Configure global application settings like school name, contact info, and branding.</p>
            </div>
            
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-6 gap-6">
                        <!-- School Name -->
                        <div class="col-span-6 sm:col-span-4">
                            <label for="school_name" class="block text-sm font-medium text-gray-700">School Name</label>
                            <input type="text" name="school_name" id="school_name" value="{{ $settings['school_name'] ?? '' }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border p-2">
                        </div>

                        <!-- School Address -->
                        <div class="col-span-6">
                            <label for="school_address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="school_address" id="school_address" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border p-2">{{ $settings['school_address'] ?? '' }}</textarea>
                        </div>

                        <!-- School Phone -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="school_phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" name="school_phone" id="school_phone" value="{{ $settings['school_phone'] ?? '' }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border p-2">
                        </div>

                        <!-- School Email -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="school_email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="school_email" id="school_email" value="{{ $settings['school_email'] ?? '' }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border p-2">
                        </div>

                        <!-- Current Session -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="current_session" class="block text-sm font-medium text-gray-700">Current Academic Session</label>
                            <input type="text" name="current_session" id="current_session" value="{{ $settings['current_session'] ?? '' }}" placeholder="e.g. 2024-2025" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border p-2">
                        </div>

                        <!-- Logo -->
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700">School Logo</label>
                            @if(isset($settings['school_logo']))
                                <div class="mt-2 mb-2">
                                    <img src="{{ asset('storage/' . $settings['school_logo']) }}" alt="School Logo" class="h-12 w-auto">
                                </div>
                            @endif
                            <input type="file" name="school_logo" class="mt-1 block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-full file:border-0
                              file:text-sm file:font-semibold
                              file:bg-indigo-50 file:text-indigo-700
                              file:hover:bg-indigo-100
                            "/>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                         <button type="submit" class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save Configuration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
