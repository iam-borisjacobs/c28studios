<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\Post;
use App\Models\PricingTier;
use App\Models\Project;
use App\Models\Service;
use App\Models\SiteSetting;
use Database\Seeders\C28ContentSeeder;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DomainSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_services_model_supports_four_tier_hierarchy_and_json_casts(): void
    {
        $service = Service::factory()->create([
            'tier' => Service::TIER_PRIMARY,
            'title' => 'DevOps & Cloud Engineering',
            'slug' => 'devops-cloud',
            'deliverables' => ['CI/CD Pipelines', 'Docker Containers'],
            'builder_blocks' => [['type' => 'hero_terminal', 'data' => ['cmd' => 'docker ps']]],
            'sort_order' => 1,
            'is_featured' => true,
            'is_active' => true,
        ]);

        $this->assertSame('Primary Service', $service->tierLabel());
        $this->assertIsArray($service->deliverables);
        $this->assertCount(2, $service->deliverables);
        $this->assertIsArray($service->builder_blocks);
        $this->assertSame('hero_terminal', $service->builder_blocks[0]['type']);
        $this->assertTrue(Service::query()->active()->featured()->ordered()->whereKey($service->id)->exists());
    }

    public function test_projects_model_enforces_work_type_distinction_and_service_relationship(): void
    {
        $service = Service::factory()->create();

        $labProject = Project::factory()->labDemonstration()->create([
            'service_id' => $service->id,
        ]);

        $clientProject = Project::factory()->clientProduction()->create([
            'service_id' => $service->id,
        ]);

        $this->assertTrue($labProject->isLabDemonstration());
        $this->assertFalse($labProject->isClientProduction());
        $this->assertSame('C28 Lab Demonstration', $labProject->workTypeLabel());

        $this->assertTrue($clientProject->isClientProduction());
        $this->assertFalse($clientProject->isLabDemonstration());
        $this->assertSame('Production Client Work', $clientProject->workTypeLabel());

        $this->assertSame($service->id, $labProject->service->id);
        $this->assertCount(2, $service->projects);

        // Deleting service should nullOnDelete service_id without deleting the project
        $service->delete();
        $this->assertNull($labProject->fresh()->service_id);
    }

    public function test_pricing_tiers_support_billing_modes_and_features_json(): void
    {
        PricingTier::factory()->create([
            'billing_mode' => PricingTier::MODE_SPRINT_FIXED,
            'price_amount' => 1200,
            'price_label' => '$1,200',
            'is_active' => true,
        ]);

        PricingTier::factory()->create([
            'billing_mode' => PricingTier::MODE_MONTHLY_RETAINER,
            'price_amount' => 750,
            'price_label' => '$750',
            'is_active' => true,
        ]);

        $this->assertCount(1, PricingTier::query()->active()->forBillingMode(PricingTier::MODE_SPRINT_FIXED)->get());
        $this->assertCount(1, PricingTier::query()->active()->forBillingMode(PricingTier::MODE_MONTHLY_RETAINER)->get());
    }

    public function test_posts_support_five_c28_categories_and_work_type_distinction(): void
    {
        foreach (array_keys(Post::CATEGORIES) as $category) {
            Post::factory()->create([
                'category' => $category,
                'work_type' => Post::WORK_TYPE_LAB_DEMONSTRATION,
                'is_published' => true,
            ]);
        }

        $this->assertCount(5, Post::query()->published()->get());
        $devopsPosts = Post::query()->byCategory(Post::CATEGORY_DEVOPS_DEMO)->get();
        $this->assertCount(1, $devopsPosts);
        $this->assertSame('Practical DevOps Demonstrations', $devopsPosts->first()->categoryLabel());
        $this->assertTrue($devopsPosts->first()->isLabDemonstration());
    }

    public function test_faqs_and_site_settings_helpers_work_reliably(): void
    {
        Faq::factory()->create(['sort_order' => 2, 'is_active' => true]);
        Faq::factory()->create(['sort_order' => 1, 'is_active' => false]);

        $this->assertCount(1, Faq::query()->active()->ordered()->get());

        SiteSetting::setValue('brand.name', 'C28 — Software & Cloud Engineering', 'brand', SiteSetting::TYPE_TEXT);
        SiteSetting::setValue('feature.console_enabled', true, 'console', SiteSetting::TYPE_BOOLEAN);
        SiteSetting::setValue('hero.metrics', ['uptime' => '99.98%'], 'hero', SiteSetting::TYPE_JSON);

        $this->assertSame('C28 — Software & Cloud Engineering', SiteSetting::getValue('brand.name'));
        $this->assertTrue(SiteSetting::getValue('feature.console_enabled'));
        $this->assertSame(['uptime' => '99.98%'], SiteSetting::getValue('hero.metrics'));
        $this->assertSame('fallback', SiteSetting::getValue('non.existent', 'fallback'));
    }

    public function test_c28_content_seeder_populates_all_domain_tables_and_is_idempotent(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(4, Service::query()->count());
        $this->assertSame(3, Project::query()->count());
        $this->assertSame(6, PricingTier::query()->count());
        $this->assertSame(5, Post::query()->count());
        $this->assertSame(5, Faq::query()->count());
        $this->assertGreaterThanOrEqual(10, SiteSetting::query()->count());

        // Running C28ContentSeeder a second time must not duplicate records
        $this->seed(C28ContentSeeder::class);

        $this->assertSame(4, Service::query()->count());
        $this->assertSame(3, Project::query()->count());
        $this->assertSame(6, PricingTier::query()->count());
        $this->assertSame(5, Post::query()->count());
        $this->assertSame(5, Faq::query()->count());
    }
}

