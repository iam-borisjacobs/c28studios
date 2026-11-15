<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Post;
use App\Models\PricingTier;
use App\Models\Project;
use App\Models\Service;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $settings = self::resolveSettings();

        try {
            $services = Service::query()->active()->ordered()->get();
        } catch (\Throwable) {
            $services = collect();
        }
        if ($services->isEmpty()) {
            $services = collect(self::defaultServices())->map(fn (array $attrs) => new Service($attrs));
        }

        try {
            $projects = Project::query()->with('service')->published()->ordered()->get();

            $sprintTiers = PricingTier::query()
                ->active()
                ->forBillingMode(PricingTier::MODE_SPRINT_FIXED)
                ->ordered()
                ->get();

            $retainerTiers = PricingTier::query()
                ->active()
                ->forBillingMode(PricingTier::MODE_MONTHLY_RETAINER)
                ->ordered()
                ->get();

            $posts = Post::query()
                ->published()
                ->orderByDesc('published_at')
                ->orderByDesc('id')
                ->limit(6)
                ->get();

            $faqs = Faq::query()->active()->ordered()->get();
        } catch (\Throwable) {
            $projects = collect();
            $sprintTiers = collect();
            $retainerTiers = collect();
            $posts = collect();
            $faqs = collect();
        }

        $codeArtifacts = $projects
            ->flatMap(fn (Project $p) => is_array($p->code_artifacts) ? $p->code_artifacts : [])
            ->unique('title')
            ->values()
            ->all();

        if (empty($codeArtifacts)) {
            $codeArtifacts = self::defaultCodeArtifacts();
        }

        return view('welcome', [
            'settings' => $settings,
            'services' => $services,
            'projects' => $projects,
            'sprintTiers' => $sprintTiers,
            'retainerTiers' => $retainerTiers,
            'posts' => $posts,
            'faqs' => $faqs,
            'codeArtifacts' => $codeArtifacts,
        ]);
    }

    /**
     * Resolve all SiteSetting values with authoritative C28 fallbacks.
     *
     * @return array<string, mixed>
     */
    public static function resolveSettings(): array
    {
        $defaults = [
            'brand.name' => 'C28 — Software & Cloud Engineering',
            'brand.statement' => 'We build digital products and help teams deploy, secure, and operate them reliably.',
            'brand.logo_top' => '',
            'brand.logo_bottom' => '',
            'brand.logo_text' => 'C28',
            'theme.mode' => 'dark',
            'hero.badge' => 'C28 — SOFTWARE & CLOUD ENGINEERING',
            'hero.headline' => 'We Build Digital Products & Help Teams Deploy, Secure, and Operate Them Reliably.',
            'hero.subheadline' => 'Instead of hiring separate people for application code, server configuration, CI/CD automation, and security, partner with one connected engineering studio built for startup founders, small SaaS teams, and growing businesses.',
            'hero.console_tabs' => [
                'pipeline' => [
                    'label' => '1. CI/CD & Docker Deploy',
                    'badge' => 'LIVE',
                    'logs' => [
                        '[12:04:01] Triggered workflow: Deploy SaaS Production',
                        '[12:04:04] Building multi-stage Docker image c28/saas-app:sha-94f2a',
                        '[12:04:12] Running automated unit & integration test suite... PASSED',
                        '[12:04:16] Pushing verified artifact to private container registry... OK',
                        '[12:04:19] Executing zero-downtime container rollout... LIVE',
                    ],
                    'outcome' => "Instead of manually logging into servers and hoping nothing breaks, your team pushes code to GitHub and C28's automated pipeline builds, scans, and releases your application reliably.",
                ],
                'devsecops' => [
                    'label' => '2. DevSecOps Gate',
                    'badge' => 'PASSED',
                    'logs' => [
                        '[SEC-01] Scanning repository for hardcoded API keys & tokens... 0 LEAKS',
                        '[SEC-02] Auditing composer.lock / package-lock.json dependencies... CLEAN',
                        '[SEC-03] Trivy container image scan (OS + library layers)... 0 CRITICAL',
                        '[SEC-04] Verifying production secrets isolated from staging... VERIFIED',
                        '[SEC-05] Security gate approved deployment to production node.',
                    ],
                    'outcome' => 'We integrate practical security checks directly into your delivery pipeline—protecting customer data, preventing leaked credentials, and catching vulnerabilities before code goes live.',
                ],
                'nginx' => [
                    'label' => '3. Linux, Nginx & HTTPS',
                    'badge' => 'TLS 1.3',
                    'logs' => [
                        '[INFRA-01] Provisioning hardened Ubuntu LTS production host... READY',
                        '[INFRA-02] Configuring Nginx upstream proxy & gzip/brotli compression... OK',
                        '[INFRA-03] Issuing & auto-renewing Let\'s Encrypt TLS 1.3 certificates... HTTPS ACTIVE',
                        '[INFRA-04] Applying UFW firewall rules & non-root container execution... LOCKED',
                        '[INFRA-05] Linux disk, memory, and endpoint health monitor online.',
                    ],
                    'outcome' => 'Your application runs on a properly configured Linux server with Nginx reverse proxy routing, automated SSL/HTTPS renewals, and proactive resource monitoring.',
                ],
                'software' => [
                    'label' => '4. SaaS & Web Architecture',
                    'badge' => 'APP',
                    'logs' => [
                        '[APP-01] Architecting multi-tenant SaaS backend & REST/GraphQL API... OK',
                        '[APP-02] Configuring database migrations, queues & Redis caching... READY',
                        '[APP-03] Building responsive customer dashboard & conversion UI... DONE',
                        '[APP-04] Connecting application telemetry & structured error logging... ACTIVE',
                        '[APP-05] Full-stack application packaged for one-click cloud deploy.',
                    ],
                    'outcome' => 'Because C28 combines web application development with cloud infrastructure engineering, the software we build is designed from day one to run smoothly in production.',
                ],
            ],
            'blueprint.steps' => [
                [
                    'step' => '01',
                    'short' => '1. Web App Code',
                    'category' => 'APPLICATION',
                    'title' => 'Build or Audit the Web Application',
                    'deliverable' => 'Deliverable: Clean Application Architecture',
                    'problem' => 'We start with your SaaS application, API, or business web app—either building it from scratch or auditing your existing codebase for production readiness.',
                    'outcome' => 'Outcome: Application structured cleanly for environment-based configuration.',
                    'cmd' => '$ git status && npm run test:ci',
                ],
                [
                    'step' => '02',
                    'short' => '2. GitHub Repo',
                    'category' => 'VERSION CONTROL',
                    'title' => 'Structure Repository & Branch Protection',
                    'deliverable' => 'Deliverable: Protected Main & Staging Branches',
                    'problem' => 'We organize your GitHub repository, remove any accidentally committed environment files, and enforce clean branch workflows.',
                    'outcome' => 'Outcome: Single source of truth for both application code and infrastructure.',
                    'cmd' => '$ git push origin main --atomic',
                ],
                [
                    'step' => '03',
                    'short' => '3. Dockerize App',
                    'category' => 'CONTAINERIZATION',
                    'title' => 'Containerize Application Using Docker',
                    'deliverable' => 'Deliverable: Multi-stage Dockerfile & Compose',
                    'problem' => 'Eliminates "it works on my laptop, but fails on the server." We package your web application, runtime dependencies, and web server into a deterministic container image.',
                    'outcome' => 'Outcome: Identical behavior across local development, staging, and production Linux hosts.',
                    'cmd' => '$ docker compose -f docker-compose.prod.yml up -d --build',
                ],
            ],
            'scenarios.items' => [
                [
                    'code' => 'S1',
                    'title' => 'SaaS Founder Scenario',
                    'subtitle' => 'Post-Development Bottleneck',
                    'quote' => 'Our developer finished writing the application code, but now we need to connect our domain, configure a production server, and get it live safely.',
                    'solution' => 'Containerized Docker setup + Nginx + HTTPS + Live Server Handoff.',
                ],
                [
                    'code' => 'S2',
                    'title' => 'Engineering Lead Scenario',
                    'subtitle' => 'Fragile Manual Releases',
                    'quote' => 'Every time we push new features to GitHub, someone has to SSH into the server manually and run commands—and deployments keep causing downtime.',
                    'solution' => 'Automated GitHub Actions CI/CD pipeline with health-checked rollouts.',
                ],
            ],
            'contact.email' => 'engineering@c28studios.com',
            'social.github_url' => 'https://github.com/iam-borisjacobs/c28studios',
            'cta.primary_label' => 'Book a Consultation',
        ];

        try {
            $stored = SiteSetting::query()->get();
            foreach ($stored as $setting) {
                $defaults[$setting->key] = match ($setting->type) {
                    SiteSetting::TYPE_JSON => $setting->json_value ?? ($defaults[$setting->key] ?? []),
                    SiteSetting::TYPE_BOOLEAN => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
                    default => $setting->value ?? ($defaults[$setting->key] ?? ''),
                };
            }
        } catch (\Throwable) {
            // Unmigrated test environment fallback
        }

        return $defaults;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected static function defaultServices(): array
    {
        return [
            [
                'tier' => Service::TIER_PRIMARY,
                'tier_badge' => '01 • Primary Service',
                'title' => 'DevOps & Cloud Engineering',
                'slug' => 'devops-cloud-engineering',
                'short_outcome' => 'Automate software delivery and infrastructure operations so your team can push code and deploy reliably.',
                'deliverables' => [
                    'CI/CD pipeline implementation (GitHub Actions)',
                    'Docker-based application deployment',
                    'Linux server, Nginx & monitoring setup',
                ],
            ],
            [
                'tier' => Service::TIER_SECONDARY,
                'tier_badge' => '02 • Secondary Service',
                'title' => 'DevSecOps Integration',
                'slug' => 'devsecops-integration',
                'short_outcome' => 'Connect practical security checks directly to your development and deployment workflows without slowing releases.',
                'deliverables' => [
                    'Dependency & vulnerability scanning',
                    'Container image security checks',
                    'Secret management & secure pipelines',
                ],
            ],
            [
                'tier' => Service::TIER_SUPPORTING,
                'tier_badge' => '03 • Supporting Service',
                'title' => 'Software Development',
                'slug' => 'software-development',
                'short_outcome' => 'Build modern web applications and digital products engineered from day one for clean deployment and maintainability.',
                'deliverables' => [
                    'SaaS applications & business web apps',
                    'APIs, backend systems & dashboards',
                    'Ongoing application maintenance',
                ],
            ],
            [
                'tier' => Service::TIER_SUPPORTING,
                'tier_badge' => '04 • Supporting Service',
                'title' => 'Website Development',
                'slug' => 'website-development',
                'short_outcome' => 'High-craft marketing websites, landing pages, and e-commerce builds optimized for speed and technical SEO.',
                'deliverables' => [
                    'Business websites & landing pages',
                    'E-commerce & website redesigns',
                    'Performance & technical SEO tuning',
                ],
            ],
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    protected static function defaultCodeArtifacts(): array
    {
        return [
            [
                'title' => '.github/workflows/deploy-production.yml',
                'language' => 'yaml',
                'code' => "# Automated C28 Production Pipeline + Security Gate\nname: Build, Scan & Deploy SaaS\non:\n  push:\n    branches: [ \"main\" ]\n\njobs:\n  secure-deploy:\n    runs-on: ubuntu-latest\n    steps:\n      - uses: actions/checkout@v4\n      - name: Build Production Docker Image\n        run: docker build -t c28-app:\${{ github.sha }} .\n      - name: DevSecOps Vulnerability & Secret Scan\n        run: trivy image --exit-code 1 --severity CRITICAL c28-app:\${{ github.sha }}\n      - name: Rollout to Production Server via SSH\n        run: ./scripts/zero-downtime-swap.sh",
            ],
            [
                'title' => 'Dockerfile.production',
                'language' => 'dockerfile',
                'code' => "# Multi-Stage Hardened Production Container\nFROM node:20-alpine AS builder\nWORKDIR /app\nCOPY package*.json ./\nRUN npm ci --only=production\nCOPY . .\nRUN npm run build\n\nFROM node:20-alpine AS runner\nRUN addgroup -S c28group && adduser -S c28user -G c28group\nWORKDIR /app\nCOPY --from=builder /app/dist ./dist\nUSER c28user\nEXPOSE 8080\nCMD [\"node\", \"dist/server.js\"]",
            ],
            [
                'title' => '/etc/nginx/conf.d/c28-app.conf',
                'language' => 'nginx',
                'code' => "# Hardened Nginx Reverse Proxy + TLS 1.3\nupstream c28_backend {\n    server 127.0.0.1:8080 keepalive=32;\n}\n\nserver {\n    listen 443 ssl http2;\n    server_name app.clientdomain.com;\n\n    ssl_protocols TLSv1.2 TLSv1.3;\n    add_header Strict-Transport-Security \"max-age=63072000\" always;\n    add_header X-Frame-Options \"SAMEORIGIN\";\n\n    location / {\n        proxy_pass http://c28_backend;\n        proxy_set_header Host \$host;\n        proxy_set_header X-Forwarded-Proto \$scheme;\n    }\n}",
            ],
        ];
    }
}
