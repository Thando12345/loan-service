<?php

namespace Tests\Feature;

use App\Models\Loan;
use App\Models\ReceivedRepayment;
use App\Models\ScheduledRepayment;
use App\Models\User;
use App\Services\LoanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $loanService;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure the SQLite database file is created
        if (!file_exists(database_path('database.sqlite'))) {
            touch(database_path('database.sqlite'));
        }
        
        // Run the migrations
        $this->artisan('migrate');
        
        $this->loanService = new LoanService();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_can_create_a_loan_with_three_month_term()
    {
        $loan = $this->loanService->createLoan($this->user->id, 3000, 3);

        $this->assertInstanceOf(Loan::class, $loan);
        $this->assertEquals(3000, $loan->amount);
        $this->assertEquals(3, $loan->term);
        $this->assertEquals('active', $loan->status);
        
        // Check if scheduled repayments were created
        $scheduledRepayments = $loan->scheduledRepayments;
        $this->assertCount(3, $scheduledRepayments);
        
        // Check if each repayment is correct
        foreach ($scheduledRepayments as $repayment) {
            $this->assertEquals(1000, $repayment->amount);
            $this->assertEquals('pending', $repayment->status);
        }
    }

    /** @test */
    public function it_can_create_a_loan_with_six_month_term()
    {
        $loan = $this->loanService->createLoan($this->user->id, 6000, 6);

        $this->assertInstanceOf(Loan::class, $loan);
        $this->assertEquals(6000, $loan->amount);
        $this->assertEquals(6, $loan->term);
        $this->assertEquals('active', $loan->status);
        
        // Check if scheduled repayments were created
        $scheduledRepayments = $loan->scheduledRepayments;
        $this->assertCount(6, $scheduledRepayments);
        
        // Check if each repayment is correct
        foreach ($scheduledRepayments as $repayment) {
            $this->assertEquals(1000, $repayment->amount);
            $this->assertEquals('pending', $repayment->status);
        }
    }

    /** @test */
    public function it_throws_exception_for_invalid_term()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Loan term must be either 3 or 6 months.');

        $this->loanService->createLoan($this->user->id, 3000, 4);
    }

    /** @test */
    public function it_can_make_full_repayment_for_scheduled_repayment()
    {
        $loan = $this->loanService->createLoan($this->user->id, 3000, 3);
        $scheduledRepayment = $loan->scheduledRepayments->first();

        $repayment = $this->loanService->makeRepayment($loan->id, $scheduledRepayment->id, 1000);

        $this->assertInstanceOf(ReceivedRepayment::class, $repayment);
        $this->assertEquals(1000, $repayment->amount);
        $this->assertEquals($loan->id, $repayment->loan_id);
        $this->assertEquals($scheduledRepayment->id, $repayment->scheduled_repayment_id);
        
        // Check if scheduled repayment status is updated
        $scheduledRepayment->refresh();
        $this->assertEquals('paid', $scheduledRepayment->status);
    }

    /** @test */
    public function it_can_make_partial_repayment_for_scheduled_repayment()
    {
        $loan = $this->loanService->createLoan($this->user->id, 3000, 3);
        $scheduledRepayment = $loan->scheduledRepayments->first();

        $repayment = $this->loanService->makeRepayment($loan->id, $scheduledRepayment->id, 500);

        $this->assertInstanceOf(ReceivedRepayment::class, $repayment);
        $this->assertEquals(500, $repayment->amount);
        $this->assertEquals($loan->id, $repayment->loan_id);
        $this->assertEquals($scheduledRepayment->id, $repayment->scheduled_repayment_id);
        
        // Check if scheduled repayment status is updated
        $scheduledRepayment->refresh();
        $this->assertEquals('partially_paid', $scheduledRepayment->status);
    }

    /** @test */
    public function it_updates_loan_status_when_fully_paid()
    {
        $loan = $this->loanService->createLoan($this->user->id, 3000, 3);
        $scheduledRepayments = $loan->scheduledRepayments;

        // Make full payment for each scheduled repayment
        foreach ($scheduledRepayments as $repayment) {
            $this->loanService->makeRepayment($loan->id, $repayment->id, $repayment->amount);
        }

        // Check if loan status is updated to paid
        $loan->refresh();
        $this->assertEquals('paid', $loan->status);
    }

    /** @test */
    public function it_throws_exception_when_payment_amount_exceeds_remaining_amount()
    {
        $loan = $this->loanService->createLoan($this->user->id, 3000, 3);
        $scheduledRepayment = $loan->scheduledRepayments->first();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Payment amount cannot exceed the remaining amount due for this scheduled repayment.');

        $this->loanService->makeRepayment($loan->id, $scheduledRepayment->id, 1500);
    }

    /** @test */
    public function it_throws_exception_when_payment_amount_is_zero_or_negative()
    {
        $loan = $this->loanService->createLoan($this->user->id, 3000, 3);
        $scheduledRepayment = $loan->scheduledRepayments->first();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Payment amount must be greater than zero.');

        $this->loanService->makeRepayment($loan->id, $scheduledRepayment->id, 0);
    }

    /** @test */
    public function it_can_calculate_remaining_balance()
    {
        $loan = $this->loanService->createLoan($this->user->id, 3000, 3);
        $scheduledRepayment = $loan->scheduledRepayments->first();

        // Make partial payment
        $this->loanService->makeRepayment($loan->id, $scheduledRepayment->id, 600);

        // Get loan details
        $loanDetails = $this->loanService->getLoanDetails($loan->id);
        
        // Check remaining balance
        $this->assertEquals(2400, $loanDetails['remaining_balance']);
        $this->assertFalse($loanDetails['is_fully_paid']);
    }

    /** @test */
    public function it_can_get_user_loans()
    {
        // Create multiple loans for the user
        $this->loanService->createLoan($this->user->id, 3000, 3);
        $this->loanService->createLoan($this->user->id, 5000, 6);
        
        // Get user loans
        $userLoans = $this->loanService->getUserLoans($this->user->id);
        
        $this->assertCount(2, $userLoans);
        $this->assertEquals(3000, $userLoans[0]->amount);
        $this->assertEquals(5000, $userLoans[1]->amount);
    }
}