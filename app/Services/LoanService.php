<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\ScheduledRepayment;
use App\Models\ReceivedRepayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class LoanService
{
    /**
     * Create a new loan with scheduled repayments.
     *
     * @param int $userId
     * @param float $amount
     * @param int $term
     * @return Loan
     */
    public function createLoan(int $userId, float $amount, int $term): Loan
    {
        // Validate term is either 3 or 6 months
        if (!in_array($term, [3, 6])) {
            throw new Exception('Loan term must be either 3 or 6 months.');
        }

        try {
            DB::beginTransaction();

            // Create the loan
            $loan = Loan::create([
                'user_id' => $userId,
                'amount' => $amount,
                'term' => $term,
                'status' => 'approved',
            ]);

            // Create scheduled repayments
            $this->createScheduledRepayments($loan);

            // Update loan status to active
            $loan->status = 'active';
            $loan->save();

            DB::commit();
            return $loan;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Create scheduled repayments for a loan.
     *
     * @param Loan $loan
     * @return void
     */
    private function createScheduledRepayments(Loan $loan): void
    {
        $amount = $loan->amount;
        $term = $loan->term;
        $monthlyAmount = $amount / $term;
        $currentDate = Carbon::now();

        for ($i = 1; $i <= $term; $i++) {
            ScheduledRepayment::create([
                'loan_id' => $loan->id,
                'amount' => $monthlyAmount,
                'due_date' => $currentDate->copy()->addMonths($i)->startOfMonth(),
                'status' => 'pending',
            ]);
        }
    }

    /**
     * Make a repayment for a scheduled repayment.
     *
     * @param int $loanId
     * @param int $scheduledRepaymentId
     * @param float $amount
     * @return ReceivedRepayment
     */
    public function makeRepayment(int $loanId, int $scheduledRepaymentId, float $amount): ReceivedRepayment
    {
        $loan = Loan::findOrFail($loanId);
        $scheduledRepayment = ScheduledRepayment::findOrFail($scheduledRepaymentId);

        // Check if repayment belongs to loan
        if ($scheduledRepayment->loan_id !== $loan->id) {
            throw new Exception('Scheduled repayment does not belong to this loan.');
        }

        // Check if amount is greater than 0
        if ($amount <= 0) {
            throw new Exception('Payment amount must be greater than zero.');
        }

        // Calculate remaining amount for the scheduled repayment
        $remainingAmount = $scheduledRepayment->getRemainingAmountAttribute();

        // Ensure payment isn't more than what's owed for this scheduled repayment
        if ($amount > $remainingAmount) {
            throw new Exception('Payment amount cannot exceed the remaining amount due for this scheduled repayment.');
        }

        try {
            DB::beginTransaction();

            // Record the received payment
            $receivedRepayment = ReceivedRepayment::create([
                'loan_id' => $loanId,
                'scheduled_repayment_id' => $scheduledRepaymentId,
                'amount' => $amount,
                'payment_date' => Carbon::now(),
            ]);

            // Update scheduled repayment status
            $this->updateScheduledRepaymentStatus($scheduledRepayment);

            // Update loan status if fully paid
            $this->updateLoanStatus($loan);

            DB::commit();
            return $receivedRepayment;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update scheduled repayment status based on payments.
     *
     * @param ScheduledRepayment $scheduledRepayment
     * @return void
     */
    private function updateScheduledRepaymentStatus(ScheduledRepayment $scheduledRepayment): void
    {
        if ($scheduledRepayment->isFullyPaid()) {
            $scheduledRepayment->status = 'paid';
        } else {
            $scheduledRepayment->status = 'partially_paid';
        }

        // Check if overdue
        if ($scheduledRepayment->status !== 'paid' && Carbon::now()->gt($scheduledRepayment->due_date)) {
            $scheduledRepayment->status = 'overdue';
        }

        $scheduledRepayment->save();
    }

    /**
     * Update loan status based on payments.
     *
     * @param Loan $loan
     * @return void
     */
    private function updateLoanStatus(Loan $loan): void
    {
        if ($loan->isFullyPaid()) {
            $loan->status = 'paid';
            $loan->save();
        }
    }

    /**
     * Get loan details with remaining balance and payment status.
     *
     * @param int $loanId
     * @return array
     */
    public function getLoanDetails(int $loanId): array
    {
        $loan = Loan::with(['scheduledRepayments', 'receivedRepayments'])->findOrFail($loanId);

        return [
            'loan' => $loan,
            'remaining_balance' => $loan->getRemainingBalanceAttribute(),
            'is_fully_paid' => $loan->isFullyPaid(),
            'scheduled_repayments' => $loan->scheduledRepayments,
            'received_repayments' => $loan->receivedRepayments,
        ];
    }

    /**
     * Get all loans for a user.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserLoans(int $userId)
    {
        return Loan::where('user_id', $userId)->get();
    }
}