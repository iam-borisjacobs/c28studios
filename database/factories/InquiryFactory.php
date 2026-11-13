<?php

namespace Database\Factories;

use App\Models\Inquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inquiry>
 */
class InquiryFactory extends Factory
{
    protected $model = Inquiry::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name() . ' — CTO',
            'email' => fake()->unique()->safeEmail(),
            'company' => fake()->company(),
            'service_interest' => fake()->randomElement([
                'DevOps & Cloud Engineering',
                'DevSecOps Integration',
                'Software Development',
                'Website Development',
            ]),
            'engagement_mode' => fake()->randomElement([
                Inquiry::MODE_SPRINT_FIXED,
                Inquiry::MODE_MONTHLY_RETAINER,
                Inquiry::MODE_ARCHITECTURE_REVIEW,
            ]),
            'project_type' => 'Existing App Needs Deployment/CI-CD',
            'budget_range' => '$1,200 – $2,500',
            'timeline' => 'Immediate (Within 1–2 Weeks)',
            'message' => 'We have a multi-tenant SaaS application that currently deploys manually over SSH and needs Docker containerization, GitHub Actions CI/CD, and Trivy scanning.',
            'status' => Inquiry::STATUS_NEW,
            'admin_notes' => null,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit Test Suite',
            'responded_at' => null,
        ];
    }
}

