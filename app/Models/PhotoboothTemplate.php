<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoboothTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'template_type',
        'orientation',
        'canvas_width',
        'canvas_height',
        'capture_count',
        'overlay_path',
        'background_path',
        'photo_slots_json',
        'timing_json',
        'is_global',
        'status',
    ];

    protected $casts = [
        'photo_slots_json' => 'array',
        'timing_json' => 'array',
        'is_global' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function eventTemplates()
    {
        return $this->hasMany(EventTemplate::class, 'photobooth_template_id');
    }

    public function steps()
    {
        return $this->hasMany(TemplateStep::class, 'photobooth_template_id');
    }

    public function boothSessions()
    {
        return $this->hasMany(BoothSession::class, 'photobooth_template_id');
    }
}
