@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Loan Details</h2>
                    <div>
                        <a href="{{ route('loans.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-100 transition">Back to Loans</a>
                        @if($loan->status == 'approved')
                            <a href="{{ route('payments.create', ['loan_id' => $loan->id]) }}" class="ml-2 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">Make Payment</a>
                        @endif
                    </div>
                </div>
                
                <!-- Loan Status Banner -->
                @if($loan->status == 'pending')
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Your loan application is currently under review. We'll notify you once a decision has been made.
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif($loan->status == 'approved')
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    Congratulations! Your loan has been approved. The funds will be disbursed to your account within 2-3 business days.
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif($loan->status == 'rejected')
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    We regret to inform you that your loan application has been rejected. Please contact our support team for more information.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Loan Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Loan Information</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Loan ID</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $loan->id }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Type</p>
                                <p class="mt-1 text-sm text-gray-900">{{ ucfirst($loan->type) }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Amount</p>
                                <p class="mt-1 text-sm text-gray-900">${{ number_format($loan->amount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Duration</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $loan->duration }} months</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Interest Rate</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $loan->interest_rate }}%</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Monthly Payment</p>
                                <p class="mt-1 text-sm text-gray-900">${{ number_format($loan->monthly_payment, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Application Date</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $loan->created_at->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <p class="mt-1">
                                    @if($loan->status == 'pending')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @elseif($loan->status == 'approved')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-green-100 text-green-800">
                                            Approved
                                        </span>
                                    @elseif($loan->status == 'rejected')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-red-100 text-red-800">
                                            Rejected
                                        </span>
                                    @elseif($loan->status == 'closed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-gray-100 text-gray-800">
                                            Closed
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Summary</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Loan Amount</p>
                                <p class="mt-1 text-sm text-gray-900">${{ number_format($loan->amount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Interest</p>
                                <p class="mt-1 text-sm text-gray-900">${{ number_format($loan->total_interest, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Amount to Pay</p>
                                <p class="mt-1 text-sm text-gray-900">${{ number_format($loan->total_payable, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Remaining Balance</p>
                                <p class="mt-1 text-sm text-gray-900">${{ number_format($loan->remaining_balance, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Payments Made</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $loan->payments_count ?? 0 }} / {{ $loan->duration }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Next Payment Due</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $loan->next_payment_date ? $loan->next_payment_date->format('M d, Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Purpose Section -->
                <div class="mt-8 bg-gray-50 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Purpose of Loan</h3>
                    <p class="text-sm text-gray-700">{{ $loan->purpose }}</p>
                </div>
                
                @if($loan->additional_info)
                <div class="mt-8 bg-gray-50 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Additional Information</h3>
                    <p class="text-sm text-gray-700">{{ $loan->additional_info }}</p>
                </div>
                @endif
                
                <!-- Payment History -->
                @if($loan->status == 'approved' || $loan->status == 'closed')
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Payment History</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Payment ID</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Amount</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Payment Date</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($loan->payments ?? [] as $payment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900">{{ $payment->id }}</td>
                                        <td class="px-3 py-4 text-sm text-gray-500">${{ number_format($payment->amount, 2) }}</td>
                                        <td class="px-3 py-4 text-sm text-gray-500">{{ $payment->payment_date->format('M d, Y') }}</td>
                                        <td class="px-3 py-4 text-sm">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-3 py-8 text-sm text-gray-500 text-center">
                                            No payment records found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection