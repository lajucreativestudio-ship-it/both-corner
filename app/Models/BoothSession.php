<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoothSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'photobooth_event_id',
        'client_device_id',
        'photobooth_template_id',
        'session_code',
        'public_token',
        'mode_type',
        'status',
        'started_at',
        'completed_at',
        'qr_code_path',
        'metadata_json',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'metadata_json' => 'array',
    ];

    public function event()
    {
        return $this->belongsTo(PhotoboothEvent::class, 'photobooth_event_id');
    }

    public function device()
    {
        return $this->belongsTo(ClientDevice::class, 'client_device_id');
    }

    public function template()
    {
        return $this->belongsTo(PhotoboothTemplate::class, 'photobooth_template_id');
    }

    public function photos()
    {
        return $this->hasMany(EventPhoto::class, 'booth_session_id');
    }
}
