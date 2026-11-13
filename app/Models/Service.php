<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    public const TIER_PRIMARY = 'primary';
    public const TIER_SECONDARY = 'secondary';
    public const TIER_SUPPORTING = 'supporting';

    public const TIERS = [
        self::TIER_PRIMARY => 'Primary Service',
        self::TIER_SECONDARY => 'Secondary Service',
        self::TIER_SUPPORTING => 'Supporting Service',
    ];

    protected $fillable = [
        'tier',
        'tier_badge',
        'title',
        'slug',
        'short_outcome',
        'description',
        'icon',
        'deliverables',
        'builder_blocks',
        'sort_order',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'deliverables' => 'array',
            'builder_blocks' => 'array',
            'sort_order' => 'integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function tierLabel(): string
    {
        return self::TIERS[$this->tier] ?? ucfirst($this->tier);
    }
}

