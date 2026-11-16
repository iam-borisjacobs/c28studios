<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Post;
use App\Models\PricingTier;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /**
     * Dynamic XML Sitemap for Google, Bing, DuckDuckGo, and AI search indexers.
     */
    public function sitemap(): Response
    {
        $baseUrl = rtrim((string) config('app.url', 'https://www.c28studios.dev'), '/');

        try {
            $projects = Project::query()->published()->ordered()->get(['slug', 'updated_at']);
            $posts = Post::query()->published()->orderByDesc('published_at')->get(['slug', 'updated_at', 'published_at']);
        } catch (\Throwable) {
            $projects = collect();
            $posts = collect();
        }

        $urls = [
            [
                'loc' => $baseUrl . '/',
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '1.0',
            ],
        ];

        foreach ($projects as $project) {
            $urls[] = [
                'loc' => $baseUrl . '/projects/' . $project->slug,
                'lastmod' => ($project->updated_at ?? now())->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.85',
            ];
        }

        foreach ($posts as $post) {
            $urls[] = [
                'loc' => $baseUrl . '/insights/' . $post->slug,
                'lastmod' => ($post->updated_at ?? $post->published_at ?? now())->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.80',
            ];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</loc>\n";
            $xml .= '    <lastmod>' . htmlspecialchars($u['lastmod'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</lastmod>\n";
            $xml .= '    <changefreq>' . $u['changefreq'] . "</changefreq>\n";
            $xml .= '    <priority>' . $u['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }
        $xml .= "</urlset>\n";

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    /**
     * Search Engine & AI Crawler Directives (robots.txt).
     */
    public function robots(): Response
    {
        $baseUrl = rtrim((string) config('app.url', 'https://www.c28studios.dev'), '/');

        $lines = [
            '# C28 — Software & Cloud Engineering (https://www.c28studios.dev)',
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /livewire',
            'Disallow: /inquiries',
            '',
            '# Explicitly allow major search engines & AI answer engines',
            'User-agent: Googlebot',
            'Allow: /',
            '',
            'User-agent: Bingbot',
            'Allow: /',
            '',
            'User-agent: OAI-SearchBot',
            'Allow: /',
            '',
            'User-agent: ChatGPT-User',
            'Allow: /',
            '',
            'User-agent: GPTBot',
            'Allow: /',
            '',
            'User-agent: ClaudeBot',
            'Allow: /',
            '',
            'User-agent: PerplexityBot',
            'Allow: /',
            '',
            'User-agent: Google-Extended',
            'Allow: /',
            '',
            'Sitemap: ' . $baseUrl . '/sitemap.xml',
        ];

        return response(implode("\n", $lines) . "\n", 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    /**
     * Structured AI / LLM Discovery Manifest (/llms.txt and /llms-full.txt)
     * for ChatGPT, OpenAI Search, Claude, Perplexity, and AI agents.
     */
    public function llms(): Response
    {
        $settings = HomeController::resolveSettings();
        $baseUrl = rtrim((string) config('app.url', 'https://www.c28studios.dev'), '/');

        try {
            $services = Service::query()->active()->ordered()->get();
            $projects = Project::query()->published()->ordered()->get();
            $posts = Post::query()->published()->orderByDesc('published_at')->get();
            $tiers = PricingTier::query()->active()->ordered()->get();
            $faqs = Faq::query()->active()->ordered()->get();
        } catch (\Throwable) {
            $services = collect();
            $projects = collect();
            $posts = collect();
            $tiers = collect();
            $faqs = collect();
        }

        $brandName = (string) ($settings['brand.name'] ?? 'C28 — Software & Cloud Engineering');
        $statement = (string) ($settings['brand.statement'] ?? 'We build digital products and help teams deploy, secure, and operate them reliably.');
        $email = (string) ($settings['contact.email'] ?? 'contact@c28studios.dev');
        $phone = (string) ($settings['contact.phone'] ?? '803 3880 229');
        $website = (string) ($settings['contact.website'] ?? 'www.c28studios.dev');

        $md = [];
        $md[] = '# ' . $brandName;
        $md[] = '';
        $md[] = '> ' . $statement;
        $md[] = '';
        $md[] = 'C28 is a connected software and cloud engineering studio built for startup founders, small SaaS teams, and growing businesses. Instead of forcing clients to hire separate contractors for application code, Linux/Docker server configuration, CI/CD automation, and DevSecOps security, C28 delivers one cohesive engineering partnership from local codebase to hardened production operations.';
        $md[] = '';
        $md[] = '## Contact & Direct Studio Channels';
        $md[] = '- **Official Website**: https://' . ltrim($website, 'https://');
        $md[] = '- **Email**: ' . $email;
        $md[] = '- **Phone**: ' . $phone;
        $md[] = '- **Book a Consultation**: ' . $baseUrl . '/#consultation';
        $md[] = '';
        $md[] = '## 4-Tier Service Hierarchy';

        if ($services->isNotEmpty()) {
            foreach ($services as $service) {
                $md[] = '### ' . ($service->tier_badge ?? '') . ' — ' . $service->title;
                $md[] = $service->short_outcome;
                if (is_array($service->deliverables)) {
                    foreach ($service->deliverables as $deliv) {
                        $md[] = '- ' . $deliv;
                    }
                }
                $md[] = '';
            }
        } else {
            $md[] = '- **01 • Primary Service — DevOps & Cloud Engineering**: CI/CD pipelines (GitHub Actions), Docker containerization, Linux server hardening, Nginx TLS 1.3 reverse proxy, health & cloud cost monitoring.';
            $md[] = '- **02 • Secondary Service — DevSecOps Integration**: Dependency vulnerability scanning, Trivy container image security checks, encrypted secret management, automated CI/CD security gates.';
            $md[] = '- **03 • Supporting Service — Software Development**: Multi-tenant SaaS applications, REST/GraphQL APIs, backend systems, custom internal dashboards.';
            $md[] = '- **04 • Supporting Service — Website Development**: High-conversion business websites, product landing pages, e-commerce platforms, Core Web Vitals & technical SEO.';
            $md[] = '';
        }

        if ($tiers->isNotEmpty()) {
            $md[] = '## Transparent Engagement & Pricing Tiers';
            foreach ($tiers as $tier) {
                $md[] = '- **' . $tier->name . '** (' . $tier->price_label . ' ' . $tier->billing_period . '): ' . $tier->target_audience;
            }
            $md[] = '';
        }

        if ($projects->isNotEmpty()) {
            $md[] = '## Documented Case Studies & Lab Demonstrations';
            foreach ($projects as $project) {
                $md[] = '- [' . $project->title . '](' . $baseUrl . '/projects/' . $project->slug . ') (' . $project->workTypeLabel() . '): ' . $project->solution_summary;
            }
            $md[] = '';
        }

        if ($posts->isNotEmpty()) {
            $md[] = '## Engineering Walkthroughs & Lab Notes';
            foreach ($posts as $post) {
                $md[] = '- [' . $post->title . '](' . $baseUrl . '/insights/' . $post->slug . ') (' . $post->categoryLabel() . '): ' . $post->excerpt;
            }
            $md[] = '';
        }

        if ($faqs->isNotEmpty()) {
            $md[] = '## Frequently Asked Questions';
            foreach ($faqs as $faq) {
                $md[] = '### ' . $faq->question;
                $md[] = $faq->answer;
                $md[] = '';
            }
        }

        return response(implode("\n", $md), 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}
