<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClearanceRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'transaction_id',
        'requested_by',
        'feedback_ar',
        'feedback_en',
        'status',
        'user_response',
        'responded_at',
        'responded_by',
        'attachments',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'attachments' => 'array',
        'responded_at' => 'datetime',
    ];

    /**
     * Get the transaction that this clearance request belongs to.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Get the user who requested the clearance.
     */
    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Get the user who responded to the clearance request.
     */
    public function respondedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    /**
     * Get the status in Arabic.
     */
    public function getStatusArAttribute(): string
    {
        return match($this->status) {
            'pending' => 'في انتظار الرد',
            'approved' => 'تم الموافقة',
            'rejected' => 'تم الرفض',
            default => $this->status,
        };
    }

    /**
     * Get the status color for UI.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get the status icon for UI.
     */
    public function getStatusIconAttribute(): string
    {
        return match($this->status) {
            'pending' => 'fa-clock',
            'approved' => 'fa-check-circle',
            'rejected' => 'fa-times-circle',
            default => 'fa-question-circle',
        };
    }

    /**
     * Scope to get pending clearance requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get approved clearance requests.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get rejected clearance requests.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Create a clearance request notification for the user.
     */
    public function sendNotificationToUser(): void
    {
        Notification::sendToUser($this->transaction->user_id, [
            'type' => 'clearance_request',
            'title_ar' => 'طلب تخليص للمعاملة',
            'title_en' => 'Clearance Request for Transaction',
            'message_ar' => "تم طلب تخليص للمعاملة رقم {$this->transaction->transaction_number}. يرجى مراجعة الملاحظات والرد على الطلب.",
            'message_en' => "A clearance request has been submitted for transaction {$this->transaction->transaction_number}. Please review the feedback and respond to the request.",
            'icon' => 'fa-clipboard-check',
            'color' => 'warning',
            'priority' => 'high',
            'channel' => 'database',
            'action_url' => route('clearance-requests.show', $this),
            'action_text_ar' => 'عرض طلب التخليص',
            'action_text_en' => 'View Clearance Request',
            'notifiable_type' => self::class,
            'notifiable_id' => $this->id,
            'data' => [
                'clearance_request_id' => $this->id,
                'transaction_id' => $this->transaction_id,
                'feedback' => $this->feedback_ar,
            ],
        ]);
    }
}

