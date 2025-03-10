@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-4">Apply for a Loan</h2>
                
                <form method="POST" action="{{ route('loans.store') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Loan Type Selection -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Loan Type</label>
                        <select id="type" name="type" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            <option value="">Select a loan type</option>
                            <option value="personal">Personal Loan</option>
                            <option value="business">Business Loan</option>
                            <option value="education">Education Loan</option>
                            <option value="mortgage">Mortgage Loan</option>
                            <option value="vehicle">Vehicle Loan</option>
                        </select>
                        @error('type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Loan Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Loan Amount ($)</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="amount" id="amount" min="100" step="100" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0.00" required>
                        </div>
                        @error('amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Loan Duration -->
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700">Loan Term (months)</label>
                        <select id="duration" name="duration" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            <option value="">Select a loan term</option>
                            <option value="6">6 months</option>
                            <option value="12">12 months</option>
                            <option value="24">24 months</option>
                            <option value="36">36 months</option>
                            <option value="48">48 months</option>
                            <option value="60">60 months</option>
                        </select>
                        @error('duration')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Purpose -->
                    <div>
                        <label for="purpose" class="block text-sm font-medium text-gray-700">Purpose of Loan</label>
                        <textarea id="purpose" name="purpose" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Briefly explain why you need this loan" required></textarea>
                        @error('purpose')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Employment Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-700">Employment Information</h3>
                        
                        <div>
                            <label for="employment_status" class="block text-sm font-medium text-gray-700">Employment Status</label>
                            <select id="employment_status" name="employment_status" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="">Select your employment status</option>
                                <option value="full_time">Full-Time Employed</option>
                                <option value="part_time">Part-Time Employed</option>
                                <option value="self_employed">Self-Employed</option>
                                <option value="unemployed">Unemployed</option>
                                <option value="retired">Retired</option>
                            </select>
                            @error('employment_status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="employer" class="block text-sm font-medium text-gray-700">Employer Name</label>
                            <input type="text" name="employer" id="employer" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            @error('employer')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="monthly_income" class="block text-sm font-medium text-gray-700">Monthly Income ($)</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" name="monthly_income" id="monthly_income" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0.00" required>
                            </div>
                            @error('monthly_income')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Additional Information -->
                    <div>
                        <label for="additional_info" class="block text-sm font-medium text-gray-700">Additional Information</label>
                        <textarea id="additional_info" name="additional_info" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Any other details you'd like to provide"></textarea>
                        @error('additional_info')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Terms Agreement -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" required>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="font-medium text-gray-700">I agree to the terms and conditions</label>
                            <p class="text-gray-500">I confirm that all information provided is accurate and complete.</p>
                            @error('terms')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection