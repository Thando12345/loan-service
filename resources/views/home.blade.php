<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Loan Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .endpoint {
            border-left: 4px solid #3b82f6;
            transition: all 0.3s ease;
        }
        .endpoint:hover {
            border-left-color: #1e40af;
            background-color: rgba(59, 130, 246, 0.05);
        }
        .method-get { color: #10b981; }
        .method-post { color: #3b82f6; }
        .method-put { color: #f59e0b; }
        .method-delete { color: #ef4444; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Loan Management System</h1>
                <div class="flex space-x-4">
                    @if (Route::has('login'))
                        <div class="flex items-center space-x-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Register</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- System Overview -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold mb-6">System Overview</h2>
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div class="flex flex-col lg:flex-row">
                    <div class="lg:w-1/2 mb-6 lg:mb-0 lg:pr-8">
                        <h3 class="text-xl font-semibold mb-4">Loan Management System Flow</h3>
                        <ol class="space-y-4 list-decimal list-inside">
                            <li>
                                <span class="font-medium">User Registration & Authentication</span>
                                <p class="text-sm text-gray-600 dark:text-gray-400 ml-5">Users register and authenticate to access the system</p>
                            </li>
                            <li>
                                <span class="font-medium">Loan Application</span>
                                <p class="text-sm text-gray-600 dark:text-gray-400 ml-5">Users apply for loans with specific terms and conditions</p>
                            </li>
                            <li>
                                <span class="font-medium">Loan Approval Process</span>
                                <p class="text-sm text-gray-600 dark:text-gray-400 ml-5">Admin reviews and approves/rejects loan applications</p>
                            </li>
                            <li>
                                <span class="font-medium">Disbursement</span>
                                <p class="text-sm text-gray-600 dark:text-gray-400 ml-5">Approved loans are disbursed to borrowers</p>
                            </li>
                            <li>
                                <span class="font-medium">Repayment Schedule</span>
                                <p class="text-sm text-gray-600 dark:text-gray-400 ml-5">System generates repayment schedules for borrowers</p>
                            </li>
                            <li>
                                <span class="font-medium">Payment Processing</span>
                                <p class="text-sm text-gray-600 dark:text-gray-400 ml-5">System tracks and processes loan repayments</p>
                            </li>
                        </ol>
                    </div>
                    
                    <div class="lg:w-1/2 lg:pl-8 lg:border-l lg:border-gray-200 dark:lg:border-gray-700">
                        <h3 class="text-xl font-semibold mb-4">System Benefits</h3>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Streamlined loan application and approval process</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Automated payment tracking and reminders</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Comprehensive reporting and analytics</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Secure user authentication and authorization</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>RESTful API for integrations with other systems</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- API Documentation -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold mb-6">API Endpoints</h2>
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="p-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Authentication Endpoints</h3>
                        <span class="px-3 py-1 text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">Sanctum Authentication</span>
                    </div>
                </div>

                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div class="p-4 endpoint">
                        <div class="flex items-center">
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded mr-3 method-get">GET</span>
                            <code class="text-sm">/api/user</code>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Get authenticated user details</p>
                    </div>

                    <div class="p-4 endpoint">
                        <div class="flex items-center">
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded mr-3 method-post">POST</span>
                            <code class="text-sm">/api/login</code>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Authenticate user and retrieve token</p>
                    </div>

                    <div class="p-4 endpoint">
                        <div class="flex items-center">
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded mr-3 method-post">POST</span>
                            <code class="text-sm">/api/register</code>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Register new user</p>
                    </div>

                    <div class="p-4 endpoint">
                        <div class="flex items-center">
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded mr-3 method-post">POST</span>
                            <code class="text-sm">/api/logout</code>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Revoke user authentication token</p>
                    </div>
                </div>

                <div class="p-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600 border-t">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Loan Management Endpoints</h3>
                        <span class="px-3 py-1 text-xs bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 rounded-full">Protected Endpoints</span>
                    </div>
                </div>

                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div class="p-4 endpoint">
                        <div class="flex items-center">
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded mr-3 method-get">GET</span>
                            <code class="text-sm">/api/loans</code>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Get all loans for authenticated user</p>
                    </div>

                    <div class="p-4 endpoint">
                        <div class="flex items-center">
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded mr-3 method-get">GET</span>
                            <code class="text-sm">/api/loans/{id}</code>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Get details for a specific loan</p>
                    </div>

                    <div class="p-4 endpoint">
                        <div class="flex items-center">
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded mr-3 method-post">POST</span>
                            <code class="text-sm">/api/loans</code>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Apply for a new loan</p>
                    </div>

                    <div class="p-4 endpoint">
                        <div class="flex items-center">
                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 rounded mr-3 method-put">PUT</span>
                            <code class="text-sm">/api/loans/{id}</code>
                        </div>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Update loan application (before approval)</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Getting Started -->
        <section>
            <h2 class="text-2xl font-bold mb-6">Getting Started</h2>
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="bg-blue-50 dark:bg-blue-900/30 p-5 rounded-lg">
                        <h3 class="text-lg font-medium mb-2">Create an Account</h3>
                        <p class="text-sm">Register for a new account to access all features of the Loan Management System.</p>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="mt-4 inline-block text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">Register Now →</a>
                        @endif
                    </div>
                    
                    <div class="bg-green-50 dark:bg-green-900/30 p-5 rounded-lg">
                        <h3 class="text-lg font-medium mb-2">Apply for a Loan</h3>
                        <p class="text-sm">Fill out our simple loan application form to get started with your loan request.</p>
                        <a href="#" class="mt-4 inline-block text-sm font-medium text-green-600 dark:text-green-400 hover:underline">View Loan Products →</a>
                    </div>
                    
                    <div class="bg-purple-50 dark:bg-purple-900/30 p-5 rounded-lg">
                        <h3 class="text-lg font-medium mb-2">API Integration</h3>
                        <p class="text-sm">Integrate our loan management API with your existing systems for seamless operations.</p>
                        <a href="#" class="mt-4 inline-block text-sm font-medium text-purple-600 dark:text-purple-400 hover:underline">API Documentation →</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    © {{ date('Y') }} Loan Management System. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>