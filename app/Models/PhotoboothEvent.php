<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoboothEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'event_date',
        'location',
        'status',
        'cover_photo_path',
        'gallery_visibility',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setting()
    {
        return $this->hasOne(EventSetting::class);
    }

    public function photos()
    {
        return $this->hasMany(EventPhoto::class);
    }

    public function eventTemplates()
    {
        return $this->hasMany(EventTemplate::class, 'photobooth_event_id');
    }

    public function eventCaptureModes()
    {
        return $this->hasMany(EventCaptureMode::class, 'photobooth_event_id');
    }

    public function boothSessions()
    {
        return $this->hasMany(BoothSession::class, 'photobooth_event_id');
    }
}
