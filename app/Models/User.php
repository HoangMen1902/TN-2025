<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRole(['Super_Admin', 'super_admin', "Super Admin", 'product staff', 'sales staff', 'marketing staff']);
    }
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'birthday',
        'avatar',
        'role',
        'user_status',
        'membership_id'
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
        'phone_verified_at' => 'datetime',
        'birthday' => 'date',
        'score' => 'integer',
        'password' => 'hashed',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user's initials.
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn($name) => Str::substr($name, 0, 1))
            ->implode('');
    }
    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'user_notifications');
    }

    public function emailChangeOtps()
    {
        return $this->hasMany(EmailChangeOtp::class);
    }

    public function activities()
    {
        return $this->hasMany(\Spatie\Activitylog\Models\Activity::class, 'causer_id');
    }
    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class, 'user_id');
    }
    public function cartItems()
    {
        return $this->hasMany(\App\Models\Cart::class, 'user_id');
    }
    public function getUserStatusBoolAttribute(): bool
    {
        return $this->user_status === 'active';
    }

    public function setUserStatusBoolAttribute($value): void
    {
        $this->user_status = $value ? 'active' : 'inactive';
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function point()
    {
        return $this->hasOne(UserPoint::class);
    }

    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }
}
