@extends('layouts.app')

@section('title', 'Record Payment')
@section('header', 'Collect Payment')

@section('content')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('payments.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-3">
                        <label for="student_id" class="block text-sm font-medium text-gray-700">Student</label>
                        <select id="student_id" name="student_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select Student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ (isset($preSelectedDetails['student_id']) && $preSelectedDetails['student_id'] == $student->id) ? 'selected' : '' }}>
                                    {{ $student->first_name }} {{ $student->last_name }} 
                                    ({{ $student->schoolClass ? $student->schoolClass->name : 'No Class' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <label for="fee_id" class="block text-sm font-medium text-gray-700">Fee Type (Invoice)</label>
                        <select id="fee_id" name="fee_id" onchange="updateAmount()" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="" data-amount="0">Select Fee (Optional)</option>
                            @foreach($fees as $fee)
                                <option value="{{ $fee->id }}" data-amount="{{ $fee->amount - $fee->paid }}" 
                                    {{ (isset($preSelectedDetails['fee_id']) && $preSelectedDetails['fee_id'] == $fee->id) ? 'selected' : '' }}>
                                    {{ $fee->type }} ({{ $fee->period }}) - Due: ${{ number_format($fee->amount - $fee->paid, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-6 sm:col-span-2">
                        <label for="amount" class="block text-sm font-medium text-gray-700">Amount Paid ($)</label>
                        <input type="number" step="0.01" name="amount" id="amount" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border p-2">
                    </div>

                    <div class="col-span-6 sm:col-span-2">
                        <label for="payment_date" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" name="payment_date" id="payment_date" value="{{ date('Y-m-d') }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border p-2">
                    </div>

                    <div class="col-span-6 sm:col-span-2">
                        <label for="method" class="block text-sm font-medium text-gray-700">Method</label>
                        <select id="method" name="method" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="cash">Cash</option>
                            <option value="check">Check</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="mobile_money">Mobile Money</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-green-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Record Payment
                    </button>
                    <a href="{{ route('payments.index') }}" class="ml-3 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateAmount() {
            var select = document.getElementById('fee_id');
            var amountInput = document.getElementById('amount');
            var selectedOption = select.options[select.selectedIndex];
            var amount = selectedOption.getAttribute('data-amount');
            
            if(amount > 0) {
                amountInput.value = amount;
            }
        }

        // Run on load if valid fee selected
        document.addEventListener('DOMContentLoaded', function() {
            updateAmount();
        });
    </script>
@endsection
