@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Payment History</h2>
                    <a href="{{ route('payments.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">Make a Payment</a>
                </div>
                
                <!-- Filter by Loan -->
                @if(count($loans ?? []) > 0)
                <div class="mb-6">
                    <label for="loan_filter" class="block text-sm font-medium text-gray-700 mb-2">Filter by Loan</label>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('payments.index') }}" class="{{ !request('loan_id') ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }} px-4 py-2 rounded-md hover:bg-gray-200 transition">
                            All Payments
                        </a>
                        @foreach($loans as $loan)
                            <a href="{{ route('payments.index', ['loan_id' => $loan->id]) }}" class="{{ request('loan_id') == $loan->id ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }} px-4 py-2 rounded-md hover:bg-gray-200 transition">
                                Loan #{{ $loan->id }} ({{ ucfirst($loan->type) }})
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Payments List -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Payment ID</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Loan ID</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Amount</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Payment Method</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Date</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($payments ?? [] as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900">{{ $payment->id }}</td>
                                    <td class="px-3 py-4 text-sm text-gray-500">
                                        <a href="{{ route('loans.show', $payment->loan) }}" class="text-indigo-600 hover:text-indigo-900">#{{ $payment->loan_id }}</a>
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-500">${{ number_format($payment->amount, 2) }}</td>
                                    <td class="px-3 py-4 text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                    <td class="px-3 py-4 text-sm text-gray-500">{{ $payment->created_at->format('M d, Y') }}</td>
                                    <td class="px-3 py-4 text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-green-100 text-green-800">
                                            Completed
                                        </span>
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-500">
                                        <a href="{{ route('payments.show', $payment) }}" class="text-indigo-600 hover:text-indigo-900">View Receipt</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-3 py-8 text-sm text-gray-500 text-center">
                                        No payment records found. 
                                        @if(count($loans ?? []) > 0)
                                            <a href="{{ route('payments.create') }}" class="text-indigo-600 hover:text-indigo-900">Make a payment now</a>
                                        @else
                                            You don't have any active loans to make payments on.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if(isset($payments) && $payments->hasPages())
                    <div class="mt-4">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection