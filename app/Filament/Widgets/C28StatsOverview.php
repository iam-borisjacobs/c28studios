<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\PricingTier;
use App\Models\Project;
use App\Models\Service;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class C28StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $activeServices = Service::query()->active()->count();

        $publishedProjects = Project::query()->published()->count();
        $clientProjects = Project::query()
            ->published()
            ->where('work_type', Project::WORK_TYPE_CLIENT_PRODUCTION)
            ->count();
        $labProjects = Project::query()
            ->published()
            ->where('work_type', Project::WORK_TYPE_LAB_DEMONSTRATION)
            ->count();

        $activePricing = PricingTier::query()->active()->count();
        $sprintTiers = PricingTier::query()
            ->active()
            ->where('billing_mode', PricingTier::MODE_SPRINT_FIXED)
            ->count();
        $retainerTiers = PricingTier::query()
            ->active()
            ->where('billing_mode', PricingTier::MODE_MONTHLY_RETAINER)
            ->count();

        $publishedPosts = Post::query()->published()->count();

        return [
            Stat::make('Active Services', (string) $activeServices)
                ->description('4-Tier Hierarchy: DevOps -> DevSecOps -> Software -> Web')
                ->color('success'),

            Stat::make('Published Projects', (string) $publishedProjects)
                ->description("{$clientProjects} Client Production • {$labProjects} Lab Demonstrations")
                ->color('info'),

            Stat::make('Active Engagement Tiers', (string) $activePricing)
                ->description("{$sprintTiers} Fixed Sprints • {$retainerTiers} Monthly Retainers")
                ->color('success'),

            Stat::make('Published Lab Notes & Posts', (string) $publishedPosts)
                ->description('Across 5 C28 Engineering Categories')
                ->color('primary'),
        ];
    }
}

