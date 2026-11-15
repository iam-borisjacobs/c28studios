<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    public const TYPE_TEXT = 'text';
    public const TYPE_JSON = 'json';
    public const TYPE_BOOLEAN = 'boolean';
    public const TYPE_IMAGE = 'image';

    protected $fillable = [
        'key',
        'group',
        'type',
        'value',
        'json_value',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'json_value' => 'array',
        ];
    }

    /**
     * Retrieve a setting value by key with automatic type resolution.
     */
    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = static::query()->where('key', $key)->first();

        if (! $setting) {
            return $default;
        }

        return match ($setting->type) {
            self::TYPE_JSON => $setting->json_value ?? $default,
            self::TYPE_BOOLEAN => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            default => $setting->value ?? $default,
        };
    }

    /**
     * Idempotently create or update a setting value by key.
     */
    public static function setValue(
        string $key,
        mixed $value,
        string $group = 'general',
        string $type = self::TYPE_TEXT,
        ?string $description = null
    ): self {
        $attributes = [
            'group' => $group,
            'type' => $type,
            'description' => $description,
        ];

        if ($type === self::TYPE_JSON || is_array($value)) {
            $attributes['type'] = self::TYPE_JSON;
            $attributes['value'] = null;
            $attributes['json_value'] = $value;
        } elseif ($type === self::TYPE_BOOLEAN || is_bool($value)) {
            $attributes['type'] = self::TYPE_BOOLEAN;
            $attributes['value'] = $value ? '1' : '0';
            $attributes['json_value'] = null;
        } else {
            $attributes['value'] = (string) $value;
            $attributes['json_value'] = null;
        }

        return static::query()->updateOrCreate(
            ['key' => $key],
            $attributes
        );
    }
}

