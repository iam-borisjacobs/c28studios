<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\PricingTier;
use App\Models\Project;
use App\Models\Service;
use App\Models\SiteSetting;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicFrontendTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_seeded_services_projects_pricing_posts_and_faqs(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->get('/');

        $response->assertStatus(200);

        // 4-Tier Service Hierarchy
        $response->assertSee('DevOps & Cloud Engineering');
        $response->assertSee('DevSecOps Integration');
        $response->assertSee('Software Development');
        $response->assertSee('Website Development');
        $response->assertSee('01 • Primary Service');
        $response->assertSee('02 • Secondary Service');
        $response->assertSee('03 • Supporting Service');
        $response->assertSee('04 • Supporting Service');

        // Seeded Projects + Mandatory Client Production vs. Lab Demonstration Distinction
        $response->assertSee('End-to-End Docker, Nginx TLS 1.3 & GitHub Actions Production Pipeline');
        $response->assertSee('Automated DevSecOps Container Vulnerability & Secret Isolation Gate');
        $response->assertSee('Full-Stack Web Platform & Hardened Cloud Control Plane');
        $response->assertSee('Production Client Work');
        $response->assertSee('C28 Lab Demonstration');

        // Seeded Pricing Tiers (Sprint Fixed + Monthly Retainer)
        $response->assertSee('Deployment & Server Setup');
        $response->assertSee('DevOps & DevSecOps Pipeline');
        $response->assertSee('Software + Cloud Engineering');
        $response->assertSee('$1,200');
        $response->assertSee('$2,500');
        $response->assertSee('$4,200+');
        $response->assertSee('$750');
        $response->assertSee('$1,600');
        $response->assertSee('$3,200');

        // Seeded Engineering Posts & FAQs
        $response->assertSee('Practical DevOps Demonstrations');
        $response->assertSee('DevSecOps & Security');
        $response->assertSee('Moving a Web Application from Localhost to Production with Docker & Nginx');
        $response->assertSee('How do Software Development, DevOps, and DevSecOps fit together at C28?');
    }

    public function test_homepage_reflects_live_database_updates_to_pricing_and_site_settings(): void
    {
        $this->seed(DatabaseSeeder::class);

        $tier = PricingTier::query()->where('slug', 'sprint-devops-devsecops-pipeline')->firstOrFail();
        $tier->update([
            'price_label' => '$9,950',
            'name' => 'Enterprise DevSecOps & HA Overhaul',
        ]);

        $service = Service::query()->where('slug', 'devops-cloud-engineering')->firstOrFail();
        $service->update([
            'short_outcome' => 'Live CMS Updated Outcome: Automated zero-downtime Docker & CI/CD pipelines.',
        ]);

        SiteSetting::setValue(
            'hero.subheadline',
            'Live CMS Updated Subheadline: End-to-end software and cloud engineering from commit to production.',
            'hero'
        );

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('$9,950');
        $response->assertSee('Enterprise DevSecOps & HA Overhaul');
        $response->assertSee('Live CMS Updated Outcome: Automated zero-downtime Docker & CI/CD pipelines.');
        $response->assertSee('Live CMS Updated Subheadline: End-to-end software and cloud engineering from commit to production.');
    }

    public function test_project_detail_page_renders_published_project_and_builder_blocks(): void
    {
        $this->seed(DatabaseSeeder::class);

        $project = Project::query()->where('is_published', true)->firstOrFail();

        $project->update([
            'builder_blocks' => [
                [
                    'type' => 'hero_terminal',
                    'data' => [
                        'title' => 'c28-prod-cluster — deploy-verify.sh',
                        'command' => './scripts/verify-zero-downtime.sh --env=production',
                        'output' => 'STATUS: UPSTREAM HEALTHY (200 OK)',
                        'status_badge' => 'VERIFIED',
                    ],
                ],
                [
                    'type' => 'metric_callout_grid',
                    'data' => [
                        'metrics' => [
                            [
                                'value' => '0s',
                                'label' => 'Release Downtime',
                                'context' => 'Measured across 50 consecutive production deploys',
                            ],
                        ],
                    ],
                ],
                [
                    'type' => 'cta_banner',
                    'data' => [
                        'heading' => 'Need this exact deployment architecture for your team?',
                        'subtext' => 'Book a technical architecture review with C28.',
                        'button_label' => 'Request Architecture Review',
                        'button_url' => '/#consultation',
                    ],
                ],
            ],
        ]);

        $response = $this->get(route('projects.show', $project));

        $response->assertStatus(200);
        $response->assertSee($project->title);
        $response->assertSee($project->workTypeLabel());
        $response->assertSee('c28-prod-cluster — deploy-verify.sh');
        $response->assertSee('./scripts/verify-zero-downtime.sh --env=production');
        $response->assertSee('Release Downtime');
        $response->assertSee('Need this exact deployment architecture for your team?');
    }

    public function test_project_detail_page_returns_404_for_unpublished_project(): void
    {
        $project = Project::factory()->create([
            'slug' => 'draft-internal-infrastructure-audit',
            'is_published' => false,
        ]);

        $response = $this->get('/projects/' . $project->slug);

        $response->assertStatus(404);
    }

    public function test_post_detail_page_renders_published_post_and_content_blocks(): void
    {
        $this->seed(DatabaseSeeder::class);

        $post = Post::query()->where('is_published', true)->firstOrFail();

        $post->update([
            'content_blocks' => [
                [
                    'type' => 'markdown_prose',
                    'data' => [
                        'section_heading' => 'Automated Rollback Verification Protocol',
                        'body' => "Every release executes an isolated container health probe before switching Nginx upstream traffic.\n\nIf the probe fails, the previous image digest remains active.",
                    ],
                ],
                [
                    'type' => 'code_artifact_viewer',
                    'data' => [
                        'filename' => 'scripts/health-gate.sh',
                        'language' => 'bash',
                        'description' => 'Pre-cutover HTTP health verification script',
                        'code' => "curl --fail --silent http://127.0.0.1:8081/up || exit 1",
                    ],
                ],
                [
                    'type' => 'problem_solution_split',
                    'data' => [
                        'problem_title' => 'Unchecked Container Cutovers',
                        'problem' => 'Switching traffic before PHP-FPM workers initialize causes 502 Bad Gateway spikes.',
                        'solution_title' => 'Deterministic Readiness Probes',
                        'solution' => 'Verify /up inside the new container network namespace prior to reloading Nginx.',
                    ],
                ],
            ],
        ]);

        $response = $this->get(route('posts.show', $post));

        $response->assertStatus(200);
        $response->assertSee($post->title);
        $response->assertSee($post->categoryLabel());
        $response->assertSee($post->workTypeLabel());
        $response->assertSee('Automated Rollback Verification Protocol');
        $response->assertSee('scripts/health-gate.sh');
        $response->assertSee('Unchecked Container Cutovers');
        $response->assertSee('Deterministic Readiness Probes');
    }

    public function test_post_detail_page_returns_404_for_unpublished_post(): void
    {
        $post = Post::factory()->create([
            'slug' => 'unpublished-draft-walkthrough',
            'is_published' => false,
            'published_at' => null,
        ]);

        $response = $this->get('/insights/' . $post->slug);

        $response->assertStatus(404);
    }
}
