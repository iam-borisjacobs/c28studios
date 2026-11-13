<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    /** @use HasFactory<\Database\Factories\InquiryFactory> */
    use HasFactory;

    public const STATUS_NEW = 'new';
    public const STATUS_IN_REVIEW = 'in_review';
    public const STATUS_RESPONDED = 'responded';
    public const STATUS_ARCHIVED = 'archived';

    public const STATUSES = [
        self::STATUS_NEW => 'New Inquiry',
        self::STATUS_IN_REVIEW => 'In Engineering Review',
        self::STATUS_RESPONDED => 'Responded',
        self::STATUS_ARCHIVED => 'Archived',
    ];

    public const MODE_SPRINT_FIXED = 'sprint_fixed';
    public const MODE_MONTHLY_RETAINER = 'monthly_retainer';
    public const MODE_ARCHITECTURE_REVIEW = 'architecture_review';

    public const ENGAGEMENT_MODES = [
        self::MODE_SPRINT_FIXED => 'Fixed-Scope Engineering Sprint',
        self::MODE_MONTHLY_RETAINER => 'Ongoing Cloud & DevSecOps Retainer',
        self::MODE_ARCHITECTURE_REVIEW => 'Discovery & Architecture Review',
    ];

    protected $fillable = [
        'name',
        'email',
        'company',
        'service_interest',
        'engagement_mode',
        'project_type',
        'budget_range',
        'timeline',
        'message',
        'status',
        'admin_notes',
        'ip_address',
        'user_agent',
        'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'responded_at' => 'datetime',
        ];
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_NEW);
    }

    public function scopeActivePipeline(Builder $query): Builder
    {
        return $query->whereIn('status', [
            self::STATUS_NEW,
            self::STATUS_IN_REVIEW,
        ]);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }

    public function engagementModeLabel(): string
    {
        return self::ENGAGEMENT_MODES[$this->engagement_mode] ?? ucfirst(str_replace('_', ' ', (string) $this->engagement_mode));
    }
}

