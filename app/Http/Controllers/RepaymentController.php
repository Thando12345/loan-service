<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\ScheduledRepayment;
use App\Services\LoanService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RepaymentController extends Controller
{
    protected $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    /**
     * Store a newly created repayment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $loanId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $loanId): JsonResponse
    {
        try {
            $validated = $request->validate([
                'scheduled_repayment_id' => 'required|exists:scheduled_repayments,id',
                'amount' => 'required|numeric|min:0.01',
            ]);

            // Check if the loan belongs to the authenticated user
            $loan = Loan::findOrFail($loanId);
            
            if ($loan->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to make repayment on this loan'
                ], 403);
            }
            
            // Check if the scheduled repayment belongs to this loan
            $scheduledRepayment = ScheduledRepayment::findOrFail($validated['scheduled_repayment_id']);
            
            if ($scheduledRepayment->loan_id !== $loan->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'The scheduled repayment does not belong to this loan'
                ], 422);
            }

            $repayment = $this->loanService->makeRepayment(
                $loanId, 
                $validated['scheduled_repayment_id'], 
                $validated['amount']
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Repayment recorded successfully',
                'data' => $repayment
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to record repayment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all scheduled repayments for a loan.
     *
     * @param  int  $loanId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getScheduledRepayments($loanId): JsonResponse
    {
        try {
            // Check if the loan belongs to the authenticated user
            $loan = Loan::findOrFail($loanId);
            
            if ($loan->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view repayments for this loan'
                ], 403);
            }
            
            $scheduledRepayments = $loan->scheduledRepayments;
            
            return response()->json([
                'success' => true,
                'data' => $scheduledRepayments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve scheduled repayments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all received repayments for a loan.
     *
     * @param  int  $loanId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReceivedRepayments($loanId): JsonResponse
    {
        try {
            // Check if the loan belongs to the authenticated user
            $loan = Loan::findOrFail($loanId);
            
            if ($loan->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view repayments for this loan'
                ], 403);
            }
            
            $receivedRepayments = $loan->receivedRepayments;
            
            return response()->json([
                'success' => true,
                'data' => $receivedRepayments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve received repayments',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}