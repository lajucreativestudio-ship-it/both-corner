<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'photobooth_event_id',
        'photobooth_template_id',
        'mode_type',
        'is_default',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(PhotoboothEvent::class, 'photobooth_event_id');
    }

    public function template()
    {
        return $this->belongsTo(PhotoboothTemplate::class, 'photobooth_template_id');
    }
}
