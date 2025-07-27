<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'name_ar',
        'email',
        'phone',
        'password',
        'user_type',
        'national_id',
        'company_name_ar',
        'commercial_register',
        'address_ar',
        'city_ar',
        'is_active',
        'last_login_at',
        'avatar',
        'profile_completed',
        'language',
        'timezone',
        'email_notifications',
        'sms_notifications',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /**
     * Get the user's display name in Arabic
     */
    public function getDisplayNameAttribute()
    {
        return $this->name_ar ?: $this->name;
    }

    /**
     * Get the user type in Arabic
     */
    public function getUserTypeArabicAttribute()
    {
        $types = [
            'individual' => 'أفراد',
            'office' => 'مكاتب هندسية',
            'developer' => 'مطورين عقاريين',
            'admin' => 'مدير'
        ];

        return $types[$this->user_type] ?? $this->user_type;
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific user type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('user_type', $type);
    }

    /**
     * Get all transactions for this user.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get all transactions assigned to this user.
     */
    public function assignedTransactions()
    {
        return $this->hasMany(Transaction::class, 'assigned_to');
    }

    /**
     * Get all attachments uploaded by this user.
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'uploaded_by');
    }

    /**
     * Get all attachments verified by this user.
     */
    public function verifiedAttachments()
    {
        return $this->hasMany(Attachment::class, 'verified_by');
    }

    /**
     * Get all notifications for this user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get unread notifications count.
     */
    public function getUnreadNotificationsCountAttribute()
    {
        return $this->notifications()->unread()->count();
    }

    /**
     * Get recent notifications.
     */
    public function getRecentNotificationsAttribute()
    {
        return $this->notifications()->recent()->orderBy('created_at', 'desc')->limit(5)->get();
    }
}
