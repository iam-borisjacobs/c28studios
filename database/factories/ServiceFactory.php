<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'tier' => fake()->randomElement(array_keys(Service::TIERS)),
            'tier_badge' => '01 • Primary Service',
            'title' => ucwords($title),
            'slug' => Str::slug($title),
            'short_outcome' => fake()->sentence(12),
            'description' => fake()->paragraph(),
            'icon' => 'server',
            'deliverables' => [
                'Automated GitHub Actions CI/CD pipeline',
                'Docker containerization & multi-stage build',
                'Hardened Linux & Nginx TLS 1.3 setup',
            ],
            'builder_blocks' => [
                [
                    'type' => 'problem_solution_split',
                    'data' => [
                        'problem' => 'Manual SSH deployments causing release friction.',
                        'solution' => 'Automated container build, scan, and zero-downtime rollout.',
                    ],
                ],
            ],
            'sort_order' => fake()->numberBetween(1, 10),
            'is_featured' => true,
            'is_active' => true,
        ];
    }
}

