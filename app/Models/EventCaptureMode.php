<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCaptureMode extends Model
{
    use HasFactory;

    protected $fillable = [
        'photobooth_event_id',
        'mode_type',
        'is_enabled',
        'config_json',
        'sort_order',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'config_json' => 'array',
        'sort_order' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(PhotoboothEvent::class, 'photobooth_event_id');
    }
}
