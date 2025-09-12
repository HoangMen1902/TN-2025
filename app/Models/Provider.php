<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class Provider extends Model
{
    protected $table = "providers";

    protected $fillable = ['provider_name', 'provider_status', 'provider_token', 'token_expired_time'];


    protected $appends = ['provider_status_bool'];

    public function getProviderStatusBoolAttribute(): bool
    {
        return $this->provider_status === 'active';
    }

    public function setProviderStatusBoolAttribute($value): void
    {
        $this->provider_status = $value ? 'active' : 'inactive';
    }

    public function setProviderTokenAttribute($value)
    {
        $this->attributes['provider_token'] = Crypt::encryptString($value);
    }

    public function getProviderTokenAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value; 
        }
    }
}
