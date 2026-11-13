<?php

namespace Database\Factories;

use App\Models\PricingTier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PricingTier>
 */
class PricingTierFactory extends Factory
{
    protected $model = PricingTier::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        $amount = fake()->randomElement([1200, 2500, 4200]);

        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'tier_badge' => '01 / LAUNCH READY',
            'billing_mode' => PricingTier::MODE_SPRINT_FIXED,
            'price_amount' => $amount,
            'price_label' => '$' . number_format($amount),
            'billing_period' => '/ typical sprint',
            'target_audience' => fake()->sentence(14),
            'features_heading' => "What's Included:",
            'features' => [
                'Docker containerization for your web app',
                'Linux server provisioning & firewall setup',
                'Nginx reverse proxy, domain & HTTPS/SSL',
            ],
            'cta_label' => 'Request Deployment Scope',
            'cta_url' => '#consultation',
            'is_highlighted' => false,
            'is_active' => true,
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }
}

