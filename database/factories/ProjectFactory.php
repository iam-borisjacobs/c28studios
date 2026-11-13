<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'service_id' => Service::factory(),
            'title' => rtrim($title, '.'),
            'slug' => Str::slug($title),
            'work_type' => Project::WORK_TYPE_LAB_DEMONSTRATION,
            'client_or_context' => 'C28 Engineering Lab',
            'problem_summary' => fake()->paragraph(),
            'solution_summary' => fake()->paragraph(),
            'outcome_metrics' => [
                ['label' => 'Release Downtime', 'value' => '0m'],
                ['label' => 'Automated Checks', 'value' => '100%'],
            ],
            'tech_stack' => ['Docker', 'GitHub Actions', 'Nginx', 'Ubuntu LTS'],
            'architecture_steps' => [
                [
                    'step' => '01',
                    'title' => 'Containerize Application Using Docker',
                    'category' => 'CONTAINERIZATION',
                    'deliverable' => 'Multi-stage Dockerfile & Compose',
                    'command' => '$ docker compose -f docker-compose.prod.yml up -d --build',
                ],
            ],
            'code_artifacts' => [
                [
                    'title' => 'Dockerfile.production',
                    'language' => 'dockerfile',
                    'code' => "FROM php:8.2-fpm-alpine\nWORKDIR /var/www/html",
                ],
            ],
            'builder_blocks' => [
                [
                    'type' => 'code_artifact_viewer',
                    'data' => [
                        'filename' => '.github/workflows/deploy-production.yml',
                        'code' => "name: Build, Scan & Deploy",
                    ],
                ],
            ],
            'repo_url' => 'https://github.com/iam-borisjacobs/c28studios',
            'live_url' => null,
            'featured_image' => null,
            'is_featured' => true,
            'is_published' => true,
            'published_at' => now(),
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }

    public function clientProduction(): static
    {
        return $this->state(fn (array $attributes) => [
            'work_type' => Project::WORK_TYPE_CLIENT_PRODUCTION,
            'client_or_context' => 'Production SaaS Architecture',
        ]);
    }

    public function labDemonstration(): static
    {
        return $this->state(fn (array $attributes) => [
            'work_type' => Project::WORK_TYPE_LAB_DEMONSTRATION,
            'client_or_context' => 'C28 Engineering Lab',
        ]);
    }
}

