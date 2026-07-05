<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_uuid',
        'api_token_hash',
        'pairing_code_id',
        'current_event_id',
        'user_id',
        'device_name',
        'platform',
        'camera_status',
        'is_online',
        'last_active_at',
        'app_version',
        'os_version',
        'ip_address',
        'last_heartbeat_at',
        'revoked_at',
    ];

    protected $casts = [
        'is_online' => 'boolean',
        'last_active_at' => 'datetime',
        'last_heartbeat_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pairingCode()
    {
        return $this->belongsTo(DevicePairingCode::class, 'pairing_code_id');
    }

    public function currentEvent()
    {
        return $this->belongsTo(PhotoboothEvent::class, 'current_event_id');
    }

    public function photos()
    {
        return $this->hasMany(EventPhoto::class);
    }

    public function isOnline(): bool
    {
        return !$this->isRevoked()
            && $this->last_heartbeat_at !== null
            && $this->last_heartbeat_at->greaterThanOrEqualTo(now()->subMinutes(2));
    }

    public function isRevoked(): bool
    {
        return $this->revoked_at !== null;
    }
}
