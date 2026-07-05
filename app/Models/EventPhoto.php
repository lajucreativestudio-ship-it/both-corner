<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'photobooth_event_id',
        'client_device_id',
        'booth_session_id',
        'user_id',
        'file_path',
        'photo_type',
        'step_number',
        'thumbnail_path',
        'original_filename',
        'mime_type',
        'file_size',
        'metadata_json',
        'public_visibility',
        'uploaded_at',
    ];

    public function boothSession()
    {
        return $this->belongsTo(BoothSession::class, 'booth_session_id');
    }

    protected function casts(): array
    {
        return [
            'metadata_json' => 'array',
            'uploaded_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(PhotoboothEvent::class, 'photobooth_event_id');
    }

    public function device()
    {
        return $this->belongsTo(ClientDevice::class, 'client_device_id');
    }
}
