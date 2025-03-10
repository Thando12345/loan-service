<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledRepayment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'loan_id',
        'amount',
        'due_date',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    /**
     * Get the loan that owns the scheduled repayment.
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Calculate remaining amount due for this scheduled repayment.
     *
     * @return float
     */
    public function getRemainingAmountAttribute(): float
    {
        $paidAmount = ReceivedRepayment::where('scheduled_repayment_id', $this->id)->sum('amount');
        return $this->amount - $paidAmount;
    }

    /**
     * Check if scheduled repayment is fully paid.
     *
     * @return bool
     */
    public function isFullyPaid(): bool
    {
        return $this->getRemainingAmountAttribute() <= 0;
    }
}