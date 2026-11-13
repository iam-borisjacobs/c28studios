<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public const CATEGORY_DEVOPS_DEMO = 'devops_demo';
    public const CATEGORY_DEVELOPER_CONVERSATION = 'developer_conversation';
    public const CATEGORY_WEB_SOFTWARE_DEV = 'web_software_dev';
    public const CATEGORY_DEVSECOPS_SECURITY = 'devsecops_security';
    public const CATEGORY_ENGINEERING_JOURNEY = 'engineering_journey';

    public const CATEGORIES = [
        self::CATEGORY_DEVOPS_DEMO => 'Practical DevOps Demonstrations',
        self::CATEGORY_DEVELOPER_CONVERSATION => 'Developer Conversations',
        self::CATEGORY_WEB_SOFTWARE_DEV => 'Website & Software Development',
        self::CATEGORY_DEVSECOPS_SECURITY => 'DevSecOps & Security',
        self::CATEGORY_ENGINEERING_JOURNEY => 'Personal Engineering Journey',
    ];

    public const WORK_TYPE_CLIENT_PRODUCTION = Project::WORK_TYPE_CLIENT_PRODUCTION;
    public const WORK_TYPE_LAB_DEMONSTRATION = Project::WORK_TYPE_LAB_DEMONSTRATION;

    public const WORK_TYPES = Project::WORK_TYPES;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'work_type',
        'hook_text',
        'excerpt',
        'content_blocks',
        'code_snippets',
        'video_url',
        'reading_time_minutes',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'content_blocks' => 'array',
            'code_snippets' => 'array',
            'reading_time_minutes' => 'integer',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function isClientProduction(): bool
    {
        return $this->work_type === self::WORK_TYPE_CLIENT_PRODUCTION;
    }

    public function isLabDemonstration(): bool
    {
        return $this->work_type === self::WORK_TYPE_LAB_DEMONSTRATION;
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? ucfirst(str_replace('_', ' ', $this->category));
    }

    public function workTypeLabel(): string
    {
        return self::WORK_TYPES[$this->work_type] ?? ucfirst(str_replace('_', ' ', $this->work_type));
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('published_at')->orderByDesc('id');
    }
}

