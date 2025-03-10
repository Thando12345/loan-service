@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-4">Loan Service Dashboard</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Active Loans Card -->
                    <div class="bg-blue-50 p-6 rounded-lg shadow-sm">
                        <h3 class="text-xl font-semibold text-blue-700">Active Loans</h3>
                        <p class="text-3xl font-bold mt-2">{{ $activeLoans ?? 0 }}</p>
                        <p class="text-sm text-gray-600 mt-1">Total outstanding loans</p>
                        <div class="mt-4">
                            <a href="{{ route('loans.index') }}" class="text-blue-700 hover:text-blue-900 text-sm font-semibold">View details →</a>
                        </div>
                    </div>
                    
                    <!-- Pending Applications Card -->
                    <div class="bg-yellow-50 p-6 rounded-lg shadow-sm">
                        <h3 class="text-xl font-semibold text-yellow-700">Pending Applications</h3>
                        <p class="text-3xl font-bold mt-2">{{ $pendingApplications ?? 0 }}</p>
                        <p class="text-sm text-gray-600 mt-1">Awaiting approval</p>
                        <div class="mt-4">
                            <a href="{{ route('loans.index', ['status' => 'pending']) }}" class="text-yellow-700 hover:text-yellow-900 text-sm font-semibold">View details →</a>
                        </div>
                    </div>
                    
                    <!-- Payment Summary Card -->
                    <div class="bg-green-50 p-6 rounded-lg shadow-sm">
                        <h3 class="text-xl font-semibold text-green-700">Upcoming Payment</h3>
                        <p class="text-3xl font-bold mt-2">{{ $nextPaymentAmount ?? '$0.00' }}</p>
                        <p class="text-sm text-gray-600 mt-1">Due: {{ $nextPaymentDate ?? 'No payments due' }}</p>
                        <div class="mt-4">
                            <a href="{{ route('payments.index') }}" class="text-green-700 hover:text-green-900 text-sm font-semibold">View payment history →</a>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions Section -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('loans.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Apply for New Loan</a>
                        <a href="{{ route('payments.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">Make a Payment</a>
                        <a href="{{ route('contact') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">Contact Support</a>
                    </div>
                </div>
                
                <!-- Recent Activities -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold mb-4">Recent Activities</h3>
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Activity</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Details</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($recentActivities ?? [] as $activity)
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">{{ $activity->type }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $activity->description }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $activity->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-500 text-center">No recent activities</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection