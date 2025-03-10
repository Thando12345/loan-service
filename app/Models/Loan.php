<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'amount',
        'term',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'term' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the loan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the scheduled repayments for the loan.
     */
    public function scheduledRepayments(): HasMany
    {
        return $this->hasMany(ScheduledRepayment::class);
    }

    /**
     * Get the received repayments for the loan.
     */
    public function receivedRepayments(): HasMany
    {
        return $this->hasMany(ReceivedRepayment::class);
    }

    /**
     * Calculate remaining balance on the loan.
     *
     * @return float
     */
    public function getRemainingBalanceAttribute(): float
    {
        $totalPaid = $this->receivedRepayments()->sum('amount');
        return $this->amount - $totalPaid;
    }

    /**
     * Check if loan is fully paid.
     *
     * @return bool
     */
    public function isFullyPaid(): bool
    {
        return $this->getRemainingBalanceAttribute() <= 0;
    }
}