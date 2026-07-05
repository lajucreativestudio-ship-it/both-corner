<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonetizationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    /**
     * Get value by key with fallback.
     */
    public static function getByKey(string $key, $fallback = null)
    {
        $setting = self::where('key', $key)->first();
        if ($setting && $setting->is_enabled) {
            return $setting->value;
        }
        return $fallback;
    }
}
