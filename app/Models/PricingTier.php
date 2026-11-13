<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingTier extends Model
{
    use HasFactory;

    public const MODE_SPRINT_FIXED = 'sprint_fixed';
    public const MODE_MONTHLY_RETAINER = 'monthly_retainer';
    public const MODE_CUSTOM = 'custom';

    public const BILLING_MODES = [
        self::MODE_SPRINT_FIXED => 'Fixed-Scope Project Sprint',
        self::MODE_MONTHLY_RETAINER => 'Ongoing Engineering Retainer',
        self::MODE_CUSTOM => 'Custom Engagement',
    ];

    protected $fillable = [
        'name',
        'slug',
        'tier_badge',
        'billing_mode',
        'price_amount',
        'price_label',
        'billing_period',
        'target_audience',
        'features_heading',
        'features',
        'cta_label',
        'cta_url',
        'is_highlighted',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price_amount' => 'integer',
            'features' => 'array',
            'is_highlighted' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeForBillingMode(Builder $query, string $mode): Builder
    {
        return $query->where('billing_mode', $mode);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function billingModeLabel(): string
    {
        return self::BILLING_MODES[$this->billing_mode] ?? ucfirst(str_replace('_', ' ', $this->billing_mode));
    }
}

