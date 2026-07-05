<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'photobooth_event_id',
        'template_id',
        'layout_type',
        'countdown_seconds',
        'capture_count',
        'retake_enabled',
        'print_enabled',
        'watermark_enabled',
        'overlay_path',
        'background_path',
        'config_json',
    ];

    protected function casts(): array
    {
        return [
            'retake_enabled' => 'boolean',
            'print_enabled' => 'boolean',
            'watermark_enabled' => 'boolean',
            'config_json' => 'array',
        ];
    }

    public function event()
    {
        return $this->belongsTo(PhotoboothEvent::class, 'photobooth_event_id');
    }

    public function template()
    {
        return $this->belongsTo(PhotoboothTemplate::class, 'template_id');
    }
}
