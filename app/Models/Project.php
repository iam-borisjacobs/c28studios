<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    public const WORK_TYPE_CLIENT_PRODUCTION = 'client_production';
    public const WORK_TYPE_LAB_DEMONSTRATION = 'lab_demonstration';

    public const WORK_TYPES = [
        self::WORK_TYPE_CLIENT_PRODUCTION => 'Production Client Work',
        self::WORK_TYPE_LAB_DEMONSTRATION => 'C28 Lab Demonstration',
    ];

    protected $fillable = [
        'service_id',
        'title',
        'slug',
        'work_type',
        'client_or_context',
        'problem_summary',
        'solution_summary',
        'outcome_metrics',
        'tech_stack',
        'architecture_steps',
        'code_artifacts',
        'builder_blocks',
        'repo_url',
        'live_url',
        'featured_image',
        'is_featured',
        'is_published',
        'published_at',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'service_id' => 'integer',
            'outcome_metrics' => 'array',
            'tech_stack' => 'array',
            'architecture_steps' => 'array',
            'code_artifacts' => 'array',
            'builder_blocks' => 'array',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'sort_order' => 'integer',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function isClientProduction(): bool
    {
        return $this->work_type === self::WORK_TYPE_CLIENT_PRODUCTION;
    }

    public function isLabDemonstration(): bool
    {
        return $this->work_type === self::WORK_TYPE_LAB_DEMONSTRATION;
    }

    public function workTypeLabel(): string
    {
        return self::WORK_TYPES[$this->work_type] ?? ucfirst(str_replace('_', ' ', $this->work_type));
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderByDesc('published_at')->orderBy('id');
    }
}

