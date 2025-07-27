<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'transaction_number',
        'type_ar',
        'type_en',
        'status',
        'municipality_ar',
        'municipality_en',
        'description_ar',
        'description_en',
        'notes',
        'admin_notes',
        'amount',
        'reference_number',
        'submission_date',
        'expected_completion_date',
        'actual_completion_date',
        'priority',
        'custom_fields',
        'assigned_to',
        'last_updated_by_user_at',
        'last_updated_by_admin_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'submission_date' => 'date',
        'expected_completion_date' => 'date',
        'actual_completion_date' => 'date',
        'custom_fields' => 'array',
        'amount' => 'decimal:2',
        'last_updated_by_user_at' => 'datetime',
        'last_updated_by_admin_at' => 'datetime',
    ];

    /**
     * Get the user that owns the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user assigned to this transaction.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get all attachments for the transaction.
     */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /**
     * Get all notifications for the transaction.
     */
    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    /**
     * Get the clearance requests for this transaction.
     */
    public function clearanceRequests(): HasMany
    {
        return $this->hasMany(ClearanceRequest::class);
    }

    /**
     * Get the latest clearance request for this transaction.
     */
    public function latestClearanceRequest()
    {
        return $this->hasOne(ClearanceRequest::class)->latestOfMany();
    }

    /**
     * Get the status in Arabic.
     */
    public function getStatusArAttribute(): string
    {
        return match($this->status) {
            'pending' => 'في انتظار المراجعة',
            'under_review' => 'قيد المراجعة',
            'completed' => 'مكتملة',
            'clearance_requested' => 'طلب تخليص',
            'clearance_approved' => 'تم الموافقة على التخليص',
            'cancelled' => 'ملغية',
            default => $this->status,
        };
    }

    /**
     * Get the priority in Arabic.
     */
    public function getPriorityArabicAttribute(): string
    {
        $priorities = [
            'low' => 'منخفضة',
            'normal' => 'عادية',
            'high' => 'عالية',
            'urgent' => 'عاجلة'
        ];

        return $priorities[$this->priority] ?? $this->priority;
    }

    /**
     * Check if transaction is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        if (!$this->expected_completion_date || $this->status === 'completed') {
            return false;
        }

        return $this->expected_completion_date->isPast();
    }

    /**
     * Get days remaining until expected completion.
     */
    public function getDaysRemainingAttribute(): ?int
    {
        if (!$this->expected_completion_date || $this->status === 'completed') {
            return null;
        }

        return now()->diffInDays($this->expected_completion_date, false);
    }

    /**
     * Scope for filtering by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by user type.
     */
    public function scopeByUserType($query, $userType)
    {
        return $query->whereHas('user', function ($q) use ($userType) {
            $q->where('user_type', $userType);
        });
    }

    /**
     * Scope for filtering by municipality.
     */
    public function scopeByMunicipality($query, $municipality)
    {
        return $query->where('municipality_ar', 'like', "%{$municipality}%");
    }

    /**
     * Scope for overdue transactions.
     */
    public function scopeOverdue($query)
    {
        return $query->where('expected_completion_date', '<', now())
                    ->whereNotIn('status', ['completed', 'cancelled']);
    }

    /**
     * Generate unique transaction number.
     */
    public static function generateTransactionNumber(): string
    {
        $prefix = 'TXN';
        $year = date('Y');
        $month = date('m');
        
        // Get the last transaction number for this month
        $lastTransaction = static::where('transaction_number', 'like', "{$prefix}-{$year}{$month}-%")
                                ->orderBy('transaction_number', 'desc')
                                ->first();

        if ($lastTransaction) {
            $lastNumber = (int) substr($lastTransaction->transaction_number, -6);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('%s-%s%s-%06d', $prefix, $year, $month, $newNumber);
    }
}
