@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-6">Make a Loan Payment</h2>
                
                @if(isset($loan) && $loan->status == 'approved')
                <div class="bg-blue-50 p-6 rounded-lg shadow-sm mb-6">
                    <h3 class="text-lg font-medium text-blue-700 mb-2">Loan Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Loan ID</p>
                            <p class="text-xl font-bold text-gray-900">{{ $loan->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Monthly Payment</p>
                            <p class="text-xl font-bold text-gray-900">${{ number_format($loan->monthly_payment, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Next Payment Due</p>
                            <p class="text-xl font-bold text-gray-900">{{ $loan->next_payment_date ? $loan->next_payment_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-500">Remaining Balance</p>
                        <p class="text-xl font-bold text-gray-900">${{ number_format($loan->remaining_balance, 2) }}</p>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('payments.store') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="loan_id" value="{{ $loan->id }}">
                    
                    <!-- Payment Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Payment Amount ($)</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="amount" id="amount" min="1" step="0.01" value="{{ $loan->monthly_payment }}" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0.00" required>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Regular monthly payment: ${{ number_format($loan->monthly_payment, 2) }}</p>
                        @error('amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Payment Method -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <select id="payment_method" name="payment_method" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            <option value="">Select a payment method</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="debit_card">Debit Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="paypal">PayPal</option>
                        </select>
                        @error('payment_method')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Payment Options Section -->
                    <div class="space-y-4 payment-details" id="credit_card_details">
                        <h3 class="text-lg font-medium text-gray-700">Credit/Debit Card Details</h3>
                        
                        <div>
                            <label for="card_number" class="block text-sm font-medium text-gray-700">Card Number</label>
                            <input type="text" name="card_number" id="card_number" placeholder="XXXX XXXX XXXX XXXX" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="expiry_date" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                                <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YY" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label for="cvv" class="block text-sm font-medium text-gray-700">CVV</label>
                                <input type="text" name="cvv" id="cvv" placeholder="XXX" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        
                        <div>
                            <label for="card_holder" class="block text-sm font-medium text-gray-700">Card Holder Name</label>
                            <input type="text" name="card_holder" id="card_holder" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('loans.show', $loan) }}" class="mr-3 text-gray-600 hover:text-gray-900">Cancel</a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Make Payment
                        </button>
                    </div>
                </form>
                @else
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Please select an active loan to make a payment.
                                </p>
                                <div class="mt-4">
                                    <a href="{{ route('loans.index', ['status' => 'approved']) }}" class="text-sm text-yellow-700 font-medium underline hover:text-yellow-600">
                                        View your active loans
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentMethodSelect = document.getElementById('payment_method');
        const creditCardDetails = document.getElementById('credit_card_details');
        
        paymentMethodSelect.addEventListener('change', function() {
            if (this.value === 'credit_card' || this.value === 'debit_card') {
                creditCardDetails.classList.remove('hidden');
            } else {
                creditCardDetails.classList.add('hidden');
            }
        });
    });
</script>
@endpush
@endsection