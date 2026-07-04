<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'hero_title',
        'hero_subtitle',
        'cta_text',
    ];
}
