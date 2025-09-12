<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use HasFactory;

    protected $table = 'user_notifications';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'notification_id',
        'notification_status',
        'is_read',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }
}
