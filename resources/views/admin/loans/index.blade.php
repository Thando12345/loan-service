@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-6">Loan Applications Management</h2>
                
                <!-- Status Filter -->
                <div class="mb-6">
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.loans.index') }}" class="{{ !request('status') ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }} px-4 py-2 rounded-md hover:bg-gray-200 transition">
                            All
                        </a>
                        <a href="{{ route('admin.loans.index', ['status' => 'pending']) }}" class="{{ request('status') == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }} px-4 py-2 rounded-md hover:bg-gray-200 transition">
                            Pending
                        </a>
                        <a href="{{ route('admin.loans.index', ['status' => 'approved']) }}" class="{{ request('status') == 'approved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} px-4 py-2 rounded-md hover:bg-gray-200 transition">
                            Approved
                        </a>
                        <a href="{{ route('admin.loans.index', ['status' => 'rejected']) }}" class="{{ request('status') == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }} px-4 py-2 rounded-md hover:bg-gray-200 transition">
                            Rejected
                        </a>
                        <a href="{{ route('admin.loans.index', ['status' => 'closed']) }}" class="{{ request('status') == 'closed' ? 'bg-gray-200 text-gray-800' : 'bg-gray-100 text-gray-800' }} px-4 py-2 rounded-md hover:bg-gray-200 transition">
                            Closed
                        </a>
                    </div>
                </div>
                
                <!-- Admin Applications List -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Loan ID</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Customer</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Type</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Amount</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Application Date</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($loans ?? [] as $loan)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900">{{ $loan->id }}</td>
                                    <td class="px-3 py-4 text-sm text-gray-500">{{ $loan->user->name }}</td>
                                    <td class="px-3 py-4 text-sm text-gray-500">{{ ucfirst($loan->type) }}</td>
                                    <td class="px-3 py-4 text-sm text-gray-500">${{ number_format($loan->amount, 2) }}</td>
                                    <td class="px-3 py-4 text-sm">
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
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-500">{{ $loan->created_at->format('M d, Y') }}</td>
                                    <td class="px-3 py-4 text-sm text-gray-500">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.loans.show', $loan) }}" class="text-indigo-600 hover:text-indigo-900">Review</a>
                                            
                                            @if($loan->status == 'pending')
                                                <form method="POST" action="{{ route('admin.loans.approve', $loan) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900 ml-2">Approve</button>
                                                </form>
                                                
                                                <form method="POST" action="{{ route('admin.loans.reject', $loan) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Reject</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-3 py-8 text-sm text-gray-500 text-center">No loan applications found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if(isset($loans) && $loans->hasPages())
                    <div class="mt-4">
                        {{ $loans->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection