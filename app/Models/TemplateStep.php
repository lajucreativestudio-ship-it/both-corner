<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'photobooth_template_id',
        'step_number',
        'slot_number',
        'countdown_seconds',
        'preview_seconds',
        'overlay_path',
        'instruction_text',
        'config_json',
    ];

    protected $casts = [
        'step_number' => 'integer',
        'slot_number' => 'integer',
        'countdown_seconds' => 'integer',
        'preview_seconds' => 'integer',
        'config_json' => 'array',
    ];

    public function template()
    {
        return $this->belongsTo(PhotoboothTemplate::class, 'photobooth_template_id');
    }
}
