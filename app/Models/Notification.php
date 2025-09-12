<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'notifications';

    protected $fillable = [
        'name',
        'content',
        'thumbnail',
        'notification_type',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_notifications');
    }

    public function userNotifications()
    {
        return $this->HasMany(UserNotification::class);
    }
}
