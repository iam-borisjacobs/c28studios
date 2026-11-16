<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use App\Models\Post;
use App\Models\Project;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityAndSeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_enforce_security_csp_and_anti_tracking_headers(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('X-DNS-Prefetch-Control', 'off');
        $response->assertHeader('Cross-Origin-Opener-Policy', 'same-origin');

        $permissionsPolicy = (string) $response->headers->get('Permissions-Policy');
        $this->assertStringContainsString('interest-cohort=()', $permissionsPolicy);
        $this->assertStringContainsString('browsing-topics=()', $permissionsPolicy);
        $this->assertStringContainsString('camera=()', $permissionsPolicy);

        $csp = (string) $response->headers->get('Content-Security-Policy');
        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringContainsString("object-src 'none'", $csp);
        $this->assertStringContainsString("frame-ancestors 'self'", $csp);
    }

    public function test_malicious_vulnerability_scanners_are_blocked_while_search_and_ai_bots_are_allowed(): void
    {
        // Malicious SQLi / vulnerability scanners receive 403 Forbidden
        $this->withHeaders(['User-Agent' => 'sqlmap/1.7.2#stable (https://sqlmap.org)'])
            ->get('/')
            ->assertStatus(403);

        $this->withHeaders(['User-Agent' => 'Mozilla/5.00 (Nikto/2.1.6)'])
            ->get('/')
            ->assertStatus(403);

        // Googlebot and OpenAI/ChatGPT crawlers are welcomed with 200 OK
        $this->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'])
            ->get('/')
            ->assertStatus(200);

        $this->withHeaders(['User-Agent' => 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko); compatible; ChatGPT-User/1.0; +https://openai.com/bot'])
            ->get('/')
            ->assertStatus(200);

        $this->withHeaders(['User-Agent' => 'OAI-SearchBot/1.0; +https://openai.com/searchbot'])
            ->get('/')
            ->assertStatus(200);
    }

    public function test_inquiry_form_blocks_xss_and_js_injection_payloads_and_strips_html_tags(): void
    {
        // 1. Explicit <script> / event handler / javascript: URI payloads are rejected with 422
        $xssResponse = $this->postJson(route('inquiries.store'), [
            'name' => '<script>alert("xss")</script>Attacker',
            'email' => 'attacker@example.com',
            'service_interest' => 'DevOps & Cloud Engineering',
            'message' => 'Trying to inject <img src=x onerror=alert(1)> into the database.',
            'website_url' => '',
        ]);

        $xssResponse->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'message']);

        $this->assertSame(0, Inquiry::query()->count());

        // 2. Benign formatting HTML tags are stripped cleanly before database storage
        $cleanResponse = $this->postJson(route('inquiries.store'), [
            'name' => '<b>Elena Vance</b>',
            'email' => 'elena@vancesaas.io',
            'company' => '<i>Vance Cloud</i>',
            'service_interest' => 'DevOps & Cloud Engineering',
            'message' => 'We need our multi-tenant Laravel SaaS containerized and deployed cleanly.',
            'website_url' => '',
        ]);

        $cleanResponse->assertStatus(201);

        $this->assertDatabaseHas('inquiries', [
            'name' => 'Elena Vance',
            'company' => 'Vance Cloud',
            'email' => 'elena@vancesaas.io',
        ]);
    }

    public function test_route_slug_constraints_reject_sql_injection_patterns_with_404(): void
    {
        $this->get("/projects/devops' OR 1=1--")->assertStatus(404);
        $this->get("/insights/post<script>alert(1)</script>")->assertStatus(404);
    }

    public function test_sitemap_xml_robots_txt_and_llms_txt_render_search_and_ai_discovery_metadata(): void
    {
        $this->seed(DatabaseSeeder::class);

        $unpublishedProject = Project::factory()->create([
            'slug' => 'secret-unpublished-project',
            'is_published' => false,
        ]);

        $unpublishedPost = Post::factory()->create([
            'slug' => 'secret-unpublished-post',
            'is_published' => false,
        ]);

        // 1. Sitemap XML
        $sitemap = $this->get('/sitemap.xml');
        $sitemap->assertStatus(200);
        $this->assertStringContainsString('application/xml', (string) $sitemap->headers->get('Content-Type'));
        $sitemap->assertSee('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', false);
        $sitemap->assertSee('/projects/zero-downtime-docker-nginx-cicd-reference', false);
        $sitemap->assertSee('/insights/moving-web-application-localhost-to-production-docker-nginx', false);
        $sitemap->assertDontSee($unpublishedProject->slug);
        $sitemap->assertDontSee($unpublishedPost->slug);

        // 2. Robots.txt
        $robots = $this->get('/robots.txt');
        $robots->assertStatus(200);
        $robots->assertSee('User-agent: Googlebot');
        $robots->assertSee('User-agent: OAI-SearchBot');
        $robots->assertSee('User-agent: ChatGPT-User');
        $robots->assertSee('User-agent: PerplexityBot');
        $robots->assertSee('Disallow: /admin');
        $robots->assertSee('/sitemap.xml');

        // 3. LLMs.txt & LLMs-full.txt for AI Discovery
        $llms = $this->get('/llms.txt');
        $llms->assertStatus(200);
        $llms->assertSee('C28 — Software & Cloud Engineering', false);
        $llms->assertSee('contact@c28studios.dev', false);
        $llms->assertSee('803 3880 229', false);
        $llms->assertSee('www.c28studios.dev', false);
        $llms->assertSee('DevOps & Cloud Engineering', false);
        $llms->assertSee('DevSecOps Integration', false);
        $llms->assertDontSee($unpublishedProject->slug);

        $this->get('/llms-full.txt')->assertStatus(200);
    }

    public function test_homepage_project_and_post_pages_render_meta_tags_canonical_links_and_json_ld_schemas(): void
    {
        $this->seed(DatabaseSeeder::class);

        // Homepage SEO & JSON-LD
        $home = $this->get('/');
        $home->assertStatus(200);
        $home->assertSee('<meta name="description"', false);
        $home->assertSee('<link rel="canonical"', false);
        $home->assertSee('<meta property="og:title"', false);
        $home->assertSee('application/ld+json', false);
        $home->assertSee('ProfessionalService', false);
        $home->assertSee('FAQPage', false);
        $home->assertSee('/llms.txt', false);

        // Project Detail SEO & JSON-LD
        $project = Project::query()->published()->firstOrFail();
        $projectResp = $this->get(route('projects.show', $project));
        $projectResp->assertStatus(200);
        $projectResp->assertSee('TechArticle', false);
        $projectResp->assertSee('BreadcrumbList', false);

        // Post Detail SEO & JSON-LD
        $post = Post::query()->published()->firstOrFail();
        $postResp = $this->get(route('posts.show', $post));
        $postResp->assertStatus(200);
        $postResp->assertSee('TechArticle', false);
        $postResp->assertSee('BreadcrumbList', false);
    }
}
