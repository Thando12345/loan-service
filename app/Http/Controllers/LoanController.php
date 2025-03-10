<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Services\LoanService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoanController extends Controller
{
    protected $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    /**
     * Display a listing of the user's loans.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $userId = Auth::id();
            $loans = $this->loanService->getUserLoans($userId);
            
            return response()->json([
                'success' => true,
                'data' => $loans
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve loans',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created loan in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'amount' => 'required|numeric|min:1',
                'term' => 'required|integer|in:3,6',
            ]);

            $userId = Auth::id();
            $loan = $this->loanService->createLoan($userId, $validated['amount'], $validated['term']);
            
            return response()->json([
                'success' => true,
                'message' => 'Loan created successfully',
                'data' => $loan
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
                'message' => 'Failed to create loan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified loan with details.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            // Check if the loan belongs to the authenticated user
            $loan = Loan::findOrFail($id);
            
            if ($loan->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to view this loan'
                ], 403);
            }
            
            $loanDetails = $this->loanService->getLoanDetails($id);
            
            return response()->json([
                'success' => true,
                'data' => $loanDetails
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve loan details',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}