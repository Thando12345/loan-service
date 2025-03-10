<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\RepaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Group routes that require authentication
Route::middleware('auth:sanctum')->group(function () {
    // Loan routes
    Route::get('/loans', [LoanController::class, 'index']);
    Route::post('/loans', [LoanController::class, 'store']);
    Route::get('/loans/{id}', [LoanController::class, 'show']);
    
  
    // Repayment routes
    Route::post('/loans/{loanId}/repayments', [RepaymentController::class, 'store']);
    Route::get('/loans/{loanId}/scheduled-repayments', [RepaymentController::class, 'getScheduledRepayments']);
    Route::get('/loans/{loanId}/received-repayments', [RepaymentController::class, 'getReceivedRepayments']);
});