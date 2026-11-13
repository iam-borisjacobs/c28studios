<?php

namespace Tests\Feature;

use App\Filament\Blocks\C28BuilderBlocks;
use App\Filament\Resources\FaqResource\Pages\CreateFaq;
use App\Filament\Resources\PostResource\Pages\CreatePost;
use App\Filament\Resources\PricingTierResource\Pages\EditPricingTier;
use App\Filament\Resources\ProjectResource\Pages\CreateProject;
use App\Filament\Resources\ServiceResource\Pages\CreateService;
use App\Filament\Resources\SiteSettingResource\Pages\EditSiteSetting;
use App\Filament\Widgets\C28StatsOverview;
use App\Models\Faq;
use App\Models\Post;
use App\Models\PricingTier;
use App\Models\Project;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminCmsResourcesTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_six_admin_resource_list_pages_enforce_authorization_and_render_for_admin(): void
    {
        $this->seed(DatabaseSeeder::class);

        $nonAdmin = User::factory()->create(['is_admin' => false]);
        $admin = User::factory()->admin()->create();

        $routes = [
            '/admin/services',
            '/admin/projects',
            '/admin/pricing-tiers',
            '/admin/posts',
            '/admin/faqs',
            '/admin/site-settings',
        ];

        foreach ($routes as $route) {
            $this->actingAs($nonAdmin)->get($route)->assertStatus(403);
            $this->actingAs($admin)->get($route)->assertStatus(200);
        }
    }

    public function test_c28_builder_blocks_library_defines_all_seven_reusable_block_types(): void
    {
        $blocks = C28BuilderBlocks::blocks();
        $names = array_map(fn ($block) => $block->getName(), $blocks);

        $this->assertSame([
            'hero_terminal',
            'architecture_step_list',
            'code_artifact_viewer',
            'metric_callout_grid',
            'problem_solution_split',
            'markdown_prose',
            'cta_banner',
        ], $names);
    }

    public function test_c28_stats_overview_widget_renders_seeded_domain_counts(): void
    {
        $this->seed(DatabaseSeeder::class);
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        Livewire::test(C28StatsOverview::class)
            ->assertSee('Active Services')
            ->assertSee('Published Projects')
            ->assertSee('1 Client Production • 2 Lab Demonstrations')
            ->assertSee('Active Engagement Tiers')
            ->assertSee('3 Fixed Sprints • 3 Monthly Retainers')
            ->assertSee('Published Lab Notes & Posts');
    }

    public function test_admin_can_create_service_with_builder_blocks_via_filament_resource(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        $builderPayload = [
            [
                'type' => 'problem_solution_split',
                'data' => [
                    'badge' => 'OUTCOME-FIRST',
                    'problem_heading' => 'Manual Releases',
                    'problem' => 'SSH deployments cause downtime.',
                    'solution_heading' => 'Automated CI/CD',
                    'solution' => 'Docker + GitHub Actions rollout.',
                    'verification_cmd' => '$ gh workflow run deploy.yml',
                ],
            ],
        ];

        Livewire::test(CreateService::class)
            ->fillForm([
                'title' => 'Cloud Infrastructure Hardening',
                'slug' => 'cloud-infrastructure-hardening',
                'tier' => Service::TIER_PRIMARY,
                'tier_badge' => '01 • Primary Service',
                'short_outcome' => 'Harden Linux hosts and automate container delivery.',
                'description' => 'Full Linux and Docker infrastructure engineering.',
                'deliverables' => ['UFW Firewall', 'TLS 1.3 Nginx'],
                'builder_blocks' => $builderPayload,
                'sort_order' => 1,
                'is_featured' => true,
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $service = Service::query()->where('slug', 'cloud-infrastructure-hardening')->firstOrFail();
        $this->assertSame(Service::TIER_PRIMARY, $service->tier);
        $this->assertCount(2, $service->deliverables);
        $this->assertSame('problem_solution_split', $service->builder_blocks[0]['type']);
    }

    public function test_admin_can_create_project_and_validates_required_work_type(): void
    {
        $admin = User::factory()->admin()->create();
        $service = Service::factory()->create();
        $this->actingAs($admin);

        // Missing required fields should fail validation
        Livewire::test(CreateProject::class)
            ->fillForm([
                'title' => '',
                'work_type' => '',
            ])
            ->call('create')
            ->assertHasFormErrors(['title' => 'required', 'work_type' => 'required']);

        Livewire::test(CreateProject::class)
            ->fillForm([
                'title' => 'Multi-Stage Docker Deployment Demo',
                'slug' => 'multi-stage-docker-deployment-demo',
                'work_type' => Project::WORK_TYPE_LAB_DEMONSTRATION,
                'service_id' => $service->id,
                'client_or_context' => 'C28 Engineering Lab',
                'problem_summary' => 'Inconsistent runtime environments across dev and prod.',
                'solution_summary' => 'Multi-stage Alpine Docker container with Trivy scan.',
                'tech_stack' => ['Docker', 'Trivy', 'Nginx'],
                'is_featured' => true,
                'is_published' => true,
                'sort_order' => 1,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $project = Project::query()->where('slug', 'multi-stage-docker-deployment-demo')->firstOrFail();
        $this->assertTrue($project->isLabDemonstration());
        $this->assertSame($service->id, $project->service_id);
    }

    public function test_admin_can_edit_pricing_tier_and_create_post_and_faq(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        $tier = PricingTier::factory()->create([
            'price_amount' => 2500,
            'price_label' => '$2,500',
        ]);

        Livewire::test(EditPricingTier::class, ['record' => $tier->getRouteKey()])
            ->fillForm([
                'price_amount' => 2900,
                'price_label' => '$2,900',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame(2900, $tier->fresh()->price_amount);
        $this->assertSame('$2,900', $tier->fresh()->price_label);

        Livewire::test(CreatePost::class)
            ->fillForm([
                'title' => 'Hardening Nginx Reverse Proxies with TLS 1.3',
                'slug' => 'hardening-nginx-reverse-proxies-tls-1-3',
                'category' => Post::CATEGORY_DEVSECOPS_SECURITY,
                'work_type' => Post::WORK_TYPE_LAB_DEMONSTRATION,
                'hook_text' => '$ sudo nginx -t && sudo systemctl reload nginx',
                'excerpt' => 'Practical TLS 1.3 and security header configuration for production web apps.',
                'reading_time_minutes' => 5,
                'is_published' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertTrue(Post::query()->where('slug', 'hardening-nginx-reverse-proxies-tls-1-3')->exists());

        Livewire::test(CreateFaq::class)
            ->fillForm([
                'question' => 'Can C28 audit our existing AWS setup?',
                'category' => 'devops',
                'answer' => 'Yes, we audit Linux servers, Docker setups, and cloud bills.',
                'sort_order' => 6,
                'is_active' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertTrue(Faq::query()->where('question', 'Can C28 audit our existing AWS setup?')->exists());
    }

    public function test_admin_can_edit_nested_json_site_setting_without_corruption(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        $setting = SiteSetting::setValue(
            'hero.console_tabs',
            [
                'pipeline' => [
                    'label' => '1. CI/CD & Docker Deploy',
                    'badge' => 'LIVE',
                ],
            ],
            'console',
            SiteSetting::TYPE_JSON,
            'Console tabs'
        );

        $updatedArray = [
            'pipeline' => [
                'label' => '1. Automated GitHub Actions Pipeline',
                'badge' => 'HEALTHY',
            ],
        ];

        Livewire::test(EditSiteSetting::class, ['record' => $setting->getRouteKey()])
            ->fillForm([
                'json_value' => json_encode($updatedArray, JSON_PRETTY_PRINT),
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame(
            '1. Automated GitHub Actions Pipeline',
            SiteSetting::getValue('hero.console_tabs')['pipeline']['label']
        );
    }
}

