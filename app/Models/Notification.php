<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'type',
        'title_ar',
        'title_en',
        'message_ar',
        'message_en',
        'icon',
        'color',
        'priority',
        'data',
        'action_url',
        'action_text_ar',
        'action_text_en',
        'is_read',
        'read_at',
        'is_sent',
        'sent_at',
        'channel',
        'notifiable_type',
        'notifiable_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'is_sent' => 'boolean',
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent notifiable model.
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the title in the appropriate language.
     */
    public function getTitleAttribute(): string
    {
        return $this->title_ar ?: $this->title_en;
    }

    /**
     * Get the message in the appropriate language.
     */
    public function getMessageAttribute(): string
    {
        return $this->message_ar ?: $this->message_en;
    }

    /**
     * Get the action text in the appropriate language.
     */
    public function getActionTextAttribute(): ?string
    {
        return $this->action_text_ar ?: $this->action_text_en;
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
     * Get the type in Arabic.
     */
    public function getTypeArabicAttribute(): string
    {
        $types = [
            'transaction_update' => 'تحديث المعاملة',
            'system_message' => 'رسالة النظام',
            'reminder' => 'تذكير',
            'welcome' => 'ترحيب',
            'warning' => 'تحذير',
            'info' => 'معلومات'
        ];

        return $types[$this->type] ?? $this->type;
    }

    /**
     * Get the channel in Arabic.
     */
    public function getChannelArabicAttribute(): string
    {
        $channels = [
            'database' => 'قاعدة البيانات',
            'email' => 'البريد الإلكتروني',
            'sms' => 'رسالة نصية'
        ];

        return $channels[$this->channel] ?? $this->channel;
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(): bool
    {
        if ($this->is_read) {
            return true;
        }

        return $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Mark notification as unread.
     */
    public function markAsUnread(): bool
    {
        return $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    /**
     * Mark notification as sent.
     */
    public function markAsSent(): bool
    {
        return $this->update([
            'is_sent' => true,
            'sent_at' => now(),
        ]);
    }

    /**
     * Scope for unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read notifications.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope for notifications by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for notifications by priority.
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for high priority notifications.
     */
    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'urgent']);
    }

    /**
     * Scope for recent notifications.
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Create a new notification.
     */
    public static function createNotification(array $data): self
    {
        return static::create($data);
    }

    /**
     * Send notification to user.
     */
    public static function sendToUser($userId, array $data): self
    {
        $data['user_id'] = $userId;
        $notification = static::createNotification($data);
        
        // Here you can add logic to send via different channels
        // For now, we'll just mark as sent for database notifications
        if ($notification->channel === 'database') {
            $notification->markAsSent();
        }
        
        return $notification;
    }
}
