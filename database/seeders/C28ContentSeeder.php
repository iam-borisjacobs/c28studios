<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Post;
use App\Models\PricingTier;
use App\Models\Project;
use App\Models\Service;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class C28ContentSeeder extends Seeder
{
    public function run(): void
    {
        $services = $this->seedServices();
        $this->seedProjects($services);
        $this->seedPricingTiers();
        $this->seedPosts();
        $this->seedFaqs();
        $this->seedSiteSettings();
    }

    /**
     * @return array<string, Service>
     */
    protected function seedServices(): array
    {
        $definitions = [
            [
                'slug' => 'devops-cloud-engineering',
                'tier' => Service::TIER_PRIMARY,
                'tier_badge' => '01 • Primary Service',
                'title' => 'DevOps & Cloud Engineering',
                'short_outcome' => 'Automate software delivery and infrastructure operations so your team can push code and deploy reliably.',
                'description' => 'We containerize applications with Docker, provision and harden Linux servers, configure Nginx reverse proxies with TLS 1.3 HTTPS, build automated GitHub Actions CI/CD pipelines, and implement uptime, resource, and cloud cost monitoring.',
                'icon' => 'server',
                'deliverables' => [
                    'CI/CD pipeline implementation (GitHub Actions)',
                    'Docker-based application deployment & Compose orchestration',
                    'Linux server hardening, Nginx reverse proxy & TLS 1.3 HTTPS',
                    'Operational health, disk/memory & cloud cost monitoring',
                ],
                'builder_blocks' => [
                    [
                        'type' => 'problem_solution_split',
                        'data' => [
                            'problem' => 'Manual SSH deployments, fragile server state, and unexpected downtime during releases.',
                            'solution' => 'Reproducible Docker images, automated GitHub Actions pipelines, and zero-downtime Nginx upstream swaps.',
                        ],
                    ],
                ],
                'sort_order' => 1,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'slug' => 'devsecops-integration',
                'tier' => Service::TIER_SECONDARY,
                'tier_badge' => '02 • Secondary Service',
                'title' => 'DevSecOps Integration',
                'short_outcome' => 'Connect practical security checks directly to your development and deployment workflows without slowing releases.',
                'description' => 'We eliminate hardcoded credentials from repositories, isolate development and production secrets, scan dependencies and Docker container images with Trivy inside CI/CD, and enforce strict HTTPS security headers.',
                'icon' => 'shield-check',
                'deliverables' => [
                    'Dependency & package vulnerability scanning',
                    'Docker container image security checks (Trivy)',
                    'Encrypted secret management & dev/prod environment isolation',
                    'Automated CI/CD security gates & Nginx header hardening',
                ],
                'builder_blocks' => [
                    [
                        'type' => 'problem_solution_split',
                        'data' => [
                            'problem' => 'Plaintext API keys in repositories and unverified container dependencies reaching production.',
                            'solution' => 'Automated CI/CD vulnerability gates and runtime-injected encrypted environment secrets.',
                        ],
                    ],
                ],
                'sort_order' => 2,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'slug' => 'software-development',
                'tier' => Service::TIER_SUPPORTING,
                'tier_badge' => '03 • Supporting Service',
                'title' => 'Software Development',
                'short_outcome' => 'Build modern web applications and digital products engineered from day one for clean deployment and maintainability.',
                'description' => 'We architect and build SaaS applications, REST/GraphQL APIs, backend systems, business web applications, and custom internal dashboards designed from the start for containerized cloud operations.',
                'icon' => 'code-bracket',
                'deliverables' => [
                    'Multi-tenant SaaS applications & business web apps',
                    'REST/GraphQL APIs, backend systems & custom dashboards',
                    'Database schema design, queues & caching architecture',
                    'Ongoing application maintenance & production readiness',
                ],
                'builder_blocks' => [
                    [
                        'type' => 'problem_solution_split',
                        'data' => [
                            'problem' => 'Applications built without operational discipline that become painful to deploy and scale.',
                            'solution' => 'Clean Laravel and Node architectures structured with stateless services, health endpoints, and container readiness.',
                        ],
                    ],
                ],
                'sort_order' => 3,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'slug' => 'website-development',
                'tier' => Service::TIER_SUPPORTING,
                'tier_badge' => '04 • Supporting Service',
                'title' => 'Website Development',
                'short_outcome' => 'High-craft marketing websites, landing pages, and e-commerce builds optimized for speed and technical SEO.',
                'description' => 'We design and engineer fast, conversion-focused business websites, product landing pages, e-commerce platforms, and technical SEO overhauls deployed on hardened infrastructure.',
                'icon' => 'globe-alt',
                'deliverables' => [
                    'High-conversion business websites & product landing pages',
                    'E-commerce platforms & complete website redesigns',
                    'Core Web Vitals performance & technical SEO tuning',
                    'CMS integration & automated deployment workflow',
                ],
                'builder_blocks' => [
                    [
                        'type' => 'problem_solution_split',
                        'data' => [
                            'problem' => 'Slow, generic templates that fail to communicate technical credibility or convert visitors.',
                            'solution' => 'Bespoke, high-speed engineering websites with clean semantic markup and technical SEO.',
                        ],
                    ],
                ],
                'sort_order' => 4,
                'is_featured' => true,
                'is_active' => true,
            ],
        ];

        $map = [];
        foreach ($definitions as $item) {
            $slug = $item['slug'];
            unset($item['slug']);
            $map[$slug] = Service::query()->updateOrCreate(['slug' => $slug], $item);
        }

        return $map;
    }

    /**
     * @param array<string, Service> $services
     */
    protected function seedProjects(array $services): void
    {
        $blueprintSteps = $this->defaultBlueprintSteps();
        $codeArtifacts = $this->defaultCodeArtifacts();

        $projects = [
            [
                'slug' => 'zero-downtime-docker-nginx-cicd-reference',
                'service_id' => $services['devops-cloud-engineering']->id ?? null,
                'title' => 'End-to-End Docker, Nginx TLS 1.3 & GitHub Actions Production Pipeline',
                'work_type' => Project::WORK_TYPE_LAB_DEMONSTRATION,
                'client_or_context' => 'C28 Engineering Lab Reference Architecture',
                'problem_summary' => 'Small engineering teams frequently deploy by SSHing into a single server and running git pull, leading to dependency drift, failed builds in production, and release downtime.',
                'solution_summary' => 'Engineered a 10-step reproducible delivery blueprint using multi-stage Docker images, Trivy CVE gates inside GitHub Actions, encrypted runtime secret injection, and an Nginx TLS 1.3 reverse proxy with health-checked upstream rollouts.',
                'outcome_metrics' => [
                    ['label' => 'Release Downtime', 'value' => '0m'],
                    ['label' => 'Automated Checks', 'value' => '100%'],
                    ['label' => 'Image Build Time', 'value' => '18.4s'],
                ],
                'tech_stack' => ['Docker', 'GitHub Actions', 'Nginx', 'Ubuntu LTS', 'Trivy', 'Let\'s Encrypt'],
                'architecture_steps' => $blueprintSteps,
                'code_artifacts' => $codeArtifacts,
                'builder_blocks' => [
                    [
                        'type' => 'code_artifact_viewer',
                        'data' => [
                            'title' => '.github/workflows/deploy-production.yml',
                            'language' => 'yaml',
                            'code' => $codeArtifacts[0]['code'],
                        ],
                    ],
                ],
                'repo_url' => 'https://github.com/iam-borisjacobs/c28studios',
                'live_url' => null,
                'featured_image' => null,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(10),
                'sort_order' => 1,
            ],
            [
                'slug' => 'devsecops-container-vulnerability-and-secret-gate',
                'service_id' => $services['devsecops-integration']->id ?? null,
                'title' => 'Automated DevSecOps Container Vulnerability & Secret Isolation Gate',
                'work_type' => Project::WORK_TYPE_LAB_DEMONSTRATION,
                'client_or_context' => 'C28 Security Engineering Lab',
                'problem_summary' => 'Application repositories often accumulate leaked environment variables, unpinned base images, and unpatched OS/library CVEs that go unnoticed until after deployment.',
                'solution_summary' => 'Implemented an automated GitHub Actions security gate combining secret scanning, Composer/NPM lockfile auditing, non-root Alpine container hardening, and Trivy CRITICAL/HIGH severity blocking before registry push.',
                'outcome_metrics' => [
                    ['label' => 'Critical CVEs Allowed', 'value' => '0'],
                    ['label' => 'Plaintext Repo Secrets', 'value' => '0'],
                    ['label' => 'Scan Overhead', 'value' => '< 15s'],
                ],
                'tech_stack' => ['Trivy', 'GitHub Actions', 'Docker', 'Alpine Linux', 'SSH Vault'],
                'architecture_steps' => array_slice($blueprintSteps, 5, 3),
                'code_artifacts' => [$codeArtifacts[0], $codeArtifacts[1]],
                'builder_blocks' => [
                    [
                        'type' => 'metric_callout_grid',
                        'data' => [
                            'heading' => 'Automated Security Gate Telemetry',
                            'metrics' => [
                                ['label' => 'Critical CVEs', 'value' => '0'],
                                ['label' => 'Secret Leaks', 'value' => '0'],
                            ],
                        ],
                    ],
                ],
                'repo_url' => 'https://github.com/iam-borisjacobs/c28studios',
                'live_url' => null,
                'featured_image' => null,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(7),
                'sort_order' => 2,
            ],
            [
                'slug' => 'multi-tenant-saas-application-and-cloud-deployment',
                'service_id' => $services['software-development']->id ?? null,
                'title' => 'Full-Stack Web Platform & Hardened Cloud Control Plane',
                'work_type' => Project::WORK_TYPE_CLIENT_PRODUCTION,
                'client_or_context' => 'Production Web & Cloud Delivery Architecture',
                'problem_summary' => 'Growing businesses often split software development and server operations across disconnected contractors, resulting in slow pages, manual content updates, and fragile hosting.',
                'solution_summary' => 'Built a unified Laravel 11 + MySQL + Filament v3 CMS control plane with dynamic Builder blocks, Tailwind CSS v3.4 obsidian/emerald frontend, and containerized Linux/Nginx deployment architecture.',
                'outcome_metrics' => [
                    ['label' => 'Core Services Unified', 'value' => '4 Tiers'],
                    ['label' => 'CMS Control Plane', 'value' => 'Filament v3'],
                    ['label' => 'Deployment Readiness', 'value' => '100%'],
                ],
                'tech_stack' => ['Laravel 11', 'MySQL 8.0', 'FilamentPHP v3', 'Tailwind CSS', 'Alpine.js', 'Docker'],
                'architecture_steps' => $blueprintSteps,
                'code_artifacts' => [$codeArtifacts[2]],
                'builder_blocks' => [
                    [
                        'type' => 'markdown_prose',
                        'data' => [
                            'heading' => 'Unified Code-to-Cloud Execution',
                            'body' => 'Engineered from day one with structured database migrations, role-gated admin controls, and automated production asset compilation.',
                        ],
                    ],
                ],
                'repo_url' => 'https://github.com/iam-borisjacobs/c28studios',
                'live_url' => null,
                'featured_image' => null,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(3),
                'sort_order' => 3,
            ],
        ];

        foreach ($projects as $item) {
            $slug = $item['slug'];
            unset($item['slug']);
            Project::query()->updateOrCreate(['slug' => $slug], $item);
        }
    }

    protected function seedPricingTiers(): void
    {
        $tiers = [
            // Fixed-Scope Project Sprints
            [
                'slug' => 'sprint-deployment-server-setup',
                'name' => 'Deployment & Server Setup',
                'tier_badge' => '01 / LAUNCH READY',
                'billing_mode' => PricingTier::MODE_SPRINT_FIXED,
                'price_amount' => 1200,
                'price_label' => '$1,200',
                'billing_period' => '/ typical sprint',
                'target_audience' => 'Ideal for founders who have a finished web or SaaS application and need it deployed cleanly and securely to production.',
                'features_heading' => "What's Included:",
                'features' => [
                    'Docker containerization for your web app',
                    'Linux server provisioning & firewall setup',
                    'Nginx reverse proxy, domain & HTTPS/SSL',
                    'Environment & production secret separation',
                    'Architecture & runbook documentation',
                ],
                'cta_label' => 'Request Deployment Scope',
                'cta_url' => '#consultation',
                'is_highlighted' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'slug' => 'sprint-devops-devsecops-pipeline',
                'name' => 'DevOps & DevSecOps Pipeline',
                'tier_badge' => '02 / CORE SPECIALIZATION',
                'billing_mode' => PricingTier::MODE_SPRINT_FIXED,
                'price_amount' => 2500,
                'price_label' => '$2,500',
                'billing_period' => '/ typical sprint',
                'target_audience' => 'Complete CI/CD automation, vulnerability scanning, and production monitoring for growing SaaS teams.',
                'features_heading' => 'Everything in Launch, plus:',
                'features' => [
                    'Automated GitHub Actions CI/CD pipelines',
                    'Automated dependency & Docker image scanning',
                    'Staging & production environment isolation',
                    'Uptime, server resource & log monitoring',
                    'Cloud cost audit & operational walkthrough',
                ],
                'cta_label' => 'Book Pipeline Consultation',
                'cta_url' => '#consultation',
                'is_highlighted' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'slug' => 'sprint-software-cloud-studio',
                'name' => 'Software + Cloud Engineering',
                'tier_badge' => '03 / END-TO-END STUDIO',
                'billing_mode' => PricingTier::MODE_SPRINT_FIXED,
                'price_amount' => 4200,
                'price_label' => '$4,200+',
                'billing_period' => '/ custom build',
                'target_audience' => 'For businesses that need a custom web application, SaaS platform, or high-performance website built and deployed under one roof.',
                'features_heading' => 'Full Studio Deliverables:',
                'features' => [
                    'Custom SaaS, web app, or business website build',
                    'Backend APIs, databases & admin dashboards',
                    'Complete Docker + CI/CD production pipeline',
                    'Integrated DevSecOps & performance SEO',
                    'Ongoing maintenance & infrastructure support',
                ],
                'cta_label' => 'Scope Your Product Build',
                'cta_url' => '#consultation',
                'is_highlighted' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
            // Ongoing Engineering Retainers
            [
                'slug' => 'retainer-deployment-server-maintenance',
                'name' => 'Deployment & Server Setup',
                'tier_badge' => '01 / LAUNCH READY',
                'billing_mode' => PricingTier::MODE_MONTHLY_RETAINER,
                'price_amount' => 750,
                'price_label' => '$750',
                'billing_period' => '/ month maintenance',
                'target_audience' => 'Ongoing Linux server patching, SSL certificate checks, backup verification, and uptime monitoring for live web applications.',
                'features_heading' => "What's Included:",
                'features' => [
                    'Docker container & Linux OS security patching',
                    'Uptime, disk & memory threshold alerting',
                    'Nginx reverse proxy & TLS certificate health',
                    'Automated database backup verification',
                    'Monthly infrastructure health report',
                ],
                'cta_label' => 'Request Retainer Scope',
                'cta_url' => '#consultation',
                'is_highlighted' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'slug' => 'retainer-devops-devsecops-partner',
                'name' => 'DevOps & DevSecOps Pipeline',
                'tier_badge' => '02 / CORE SPECIALIZATION',
                'billing_mode' => PricingTier::MODE_MONTHLY_RETAINER,
                'price_amount' => 1600,
                'price_label' => '$1,600',
                'billing_period' => '/ month DevOps partner',
                'target_audience' => 'Dedicated CI/CD pipeline evolution, DevSecOps vulnerability remediation, and cloud cost optimization for scaling SaaS teams.',
                'features_heading' => 'Everything in Launch Retainer, plus:',
                'features' => [
                    'Continuous GitHub Actions CI/CD improvements',
                    'Ongoing Trivy CVE & dependency remediation',
                    'Staging & production release support',
                    'Log aggregation & performance tuning',
                    'Monthly cloud cost optimization review',
                ],
                'cta_label' => 'Book Retainer Consultation',
                'cta_url' => '#consultation',
                'is_highlighted' => true,
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'slug' => 'retainer-dedicated-software-cloud-studio',
                'name' => 'Software + Cloud Engineering',
                'tier_badge' => '03 / END-TO-END STUDIO',
                'billing_mode' => PricingTier::MODE_MONTHLY_RETAINER,
                'price_amount' => 3200,
                'price_label' => '$3,200',
                'billing_period' => '/ month dedicated studio',
                'target_audience' => 'Full-stack feature development, API engineering, and managed cloud operations with one accountable engineering partner.',
                'features_heading' => 'Full Studio Retainer Deliverables:',
                'features' => [
                    'Ongoing SaaS & web application feature sprints',
                    'Backend API, database & dashboard evolution',
                    'Managed CI/CD pipelines & zero-downtime releases',
                    'Continuous DevSecOps & technical SEO tuning',
                    'Priority architectural & incident response',
                ],
                'cta_label' => 'Scope Dedicated Studio',
                'cta_url' => '#consultation',
                'is_highlighted' => false,
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($tiers as $item) {
            $slug = $item['slug'];
            unset($item['slug']);
            PricingTier::query()->updateOrCreate(['slug' => $slug], $item);
        }
    }

    protected function seedPosts(): void
    {
        $posts = [
            [
                'slug' => 'moving-web-application-localhost-to-production-docker-nginx',
                'title' => 'Moving a Web Application from Localhost to Production with Docker & Nginx',
                'category' => Post::CATEGORY_DEVOPS_DEMO,
                'work_type' => Post::WORK_TYPE_LAB_DEMONSTRATION,
                'hook_text' => '$ docker compose up -d -> Container app_web Started -> Nginx SSL 443 Active',
                'excerpt' => 'How the pieces fit together when containerizing a sample web application, configuring reverse proxy routing, and resolving common permission pitfalls.',
                'content_blocks' => [
                    [
                        'type' => 'markdown_prose',
                        'data' => [
                            'heading' => '1. Containerizing the Runtime',
                            'body' => 'Instead of installing PHP, Node, or Python versions directly on the host OS, we package the application inside a multi-stage Dockerfile so the exact same dependencies run everywhere.',
                        ],
                    ],
                    [
                        'type' => 'markdown_prose',
                        'data' => [
                            'heading' => '2. Nginx Reverse Proxy & HTTPS',
                            'body' => 'We place Nginx in front of the container to handle incoming traffic, compress static assets, and terminate TLS 1.3 SSL certificates via Let\'s Encrypt.',
                        ],
                    ],
                    [
                        'type' => 'markdown_prose',
                        'data' => [
                            'heading' => '3. The Result',
                            'body' => 'Predictable releases that do not break when server packages update, backed by a clean docker-compose production definition.',
                        ],
                    ],
                ],
                'code_snippets' => [
                    [
                        'title' => 'Terminal Verification',
                        'language' => 'bash',
                        'code' => "$ docker compose -f docker-compose.prod.yml up -d --build\n$ sudo nginx -t && sudo systemctl reload nginx",
                    ],
                ],
                'video_url' => null,
                'reading_time_minutes' => 6,
                'is_published' => true,
                'published_at' => now()->subDays(12),
            ],
            [
                'slug' => 'where-security-checks-fit-inside-github-actions-pipeline',
                'title' => 'Where Security Checks Fit Inside an Automated GitHub Actions Pipeline',
                'category' => Post::CATEGORY_DEVSECOPS_SECURITY,
                'work_type' => Post::WORK_TYPE_LAB_DEMONSTRATION,
                'hook_text' => '$ trivy image c28/web:latest -> Total: 0 (CRITICAL: 0) -> Secrets Vault Verified',
                'excerpt' => 'Why storing API keys in repositories is dangerous, how to separate dev and production secrets, and how automated image scanning catches CVEs early.',
                'content_blocks' => [
                    [
                        'type' => 'markdown_prose',
                        'data' => [
                            'heading' => '1. Eliminating Hardcoded Secrets',
                            'body' => 'API keys and database credentials should never live in source code. We migrate secrets into encrypted environment vaults injected only at runtime.',
                        ],
                    ],
                    [
                        'type' => 'markdown_prose',
                        'data' => [
                            'heading' => '2. Dependency & Image Scanning',
                            'body' => 'Before a Docker image is deployed, Trivy scans both OS packages and language dependencies for known CVEs.',
                        ],
                    ],
                    [
                        'type' => 'markdown_prose',
                        'data' => [
                            'heading' => '3. Automated Gatekeeping',
                            'body' => 'If a critical vulnerability is detected, the pipeline halts automatically—keeping your production environment safe.',
                        ],
                    ],
                ],
                'code_snippets' => [
                    [
                        'title' => 'Trivy Security Gate Step',
                        'language' => 'yaml',
                        'code' => "- name: DevSecOps Vulnerability & Secret Scan\n  run: trivy image --exit-code 1 --severity CRITICAL c28-app:\${{ github.sha }}",
                    ],
                ],
                'video_url' => null,
                'reading_time_minutes' => 5,
                'is_published' => true,
                'published_at' => now()->subDays(9),
            ],
            [
                'slug' => 'what-building-web-applications-taught-us-about-reliable-deployment',
                'title' => 'What Building Web Applications Taught Us About Reliable Deployment',
                'category' => Post::CATEGORY_DEVELOPER_CONVERSATION,
                'work_type' => Post::WORK_TYPE_LAB_DEMONSTRATION,
                'hook_text' => 'App Code <-> Cloud Infra -> Structured Laravel/Node API -> Zero-Downtime Release',
                'excerpt' => 'Why connecting software development and cloud engineering solves the biggest operational headaches for small SaaS teams and startup founders.',
                'content_blocks' => [
                    [
                        'type' => 'markdown_prose',
                        'data' => [
                            'heading' => '1. Designing for Deployment',
                            'body' => 'Experience building Laravel, Node, and SaaS applications makes it much easier to design stateless services, clean health-check endpoints, and structured logging.',
                        ],
                    ],
                    [
                        'type' => 'markdown_prose',
                        'data' => [
                            'heading' => '2. One Connected Partner',
                            'body' => 'At C28, we bridge application development with DevOps and DevSecOps—so founders have one engineering partner accountable for both the product and the platform it runs on.',
                        ],
                    ],
                ],
                'code_snippets' => [
                    [
                        'title' => 'Health Check Verification',
                        'language' => 'bash',
                        'code' => '$ curl -fsS https://app.yourdomain.com/up',
                    ],
                ],
                'video_url' => null,
                'reading_time_minutes' => 4,
                'is_published' => true,
                'published_at' => now()->subDays(6),
            ],
            [
                'slug' => 'architecting-laravel-and-blade-platforms-for-production-speed',
                'title' => 'Architecting Laravel & Blade Platforms for Production Speed and Clean Operations',
                'category' => Post::CATEGORY_WEB_SOFTWARE_DEV,
                'work_type' => Post::WORK_TYPE_CLIENT_PRODUCTION,
                'hook_text' => 'Laravel 11 + MySQL 8.0 + Filament v3 -> Isolated Vite Bundle -> Sub-50ms TTFB',
                'excerpt' => 'How we structure full-stack web applications and administrative control planes so content editors get full flexibility without bloating the public frontend.',
                'content_blocks' => [
                    [
                        'type' => 'markdown_prose',
                        'data' => [
                            'heading' => 'Isolating Admin and Public Assets',
                            'body' => 'By keeping public pages on compiled Blade, Tailwind CSS, and lightweight Alpine/Vanilla JS while isolating Livewire to the /admin control plane, public pages stay fast and deterministic.',
                        ],
                    ],
                ],
                'code_snippets' => [
                    [
                        'title' => 'Vite Asset Isolation',
                        'language' => 'blade',
                        'code' => "@vite(['resources/css/app.css', 'resources/js/app.js'])",
                    ],
                ],
                'video_url' => null,
                'reading_time_minutes' => 5,
                'is_published' => true,
                'published_at' => now()->subDays(4),
            ],
            [
                'slug' => 'why-we-built-c28-to-bridge-software-and-cloud-engineering',
                'title' => 'Why We Built C28 to Bridge Software Development and Cloud Engineering',
                'category' => Post::CATEGORY_ENGINEERING_JOURNEY,
                'work_type' => Post::WORK_TYPE_LAB_DEMONSTRATION,
                'hook_text' => 'Code -> Container -> Hardened Server -> Monitored Operations',
                'excerpt' => 'The engineering journey behind combining full-stack application development with practical Linux, Docker, CI/CD, and DevSecOps execution.',
                'content_blocks' => [
                    [
                        'type' => 'markdown_prose',
                        'data' => [
                            'heading' => 'Show What We Know by Demonstrating What We Do',
                            'body' => 'Rather than listing abstract cloud buzzwords, C28 documents real deployment blueprints, verifiable configurations, and transparent lab demonstrations alongside production client delivery.',
                        ],
                    ],
                ],
                'code_snippets' => [],
                'video_url' => null,
                'reading_time_minutes' => 4,
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
        ];

        foreach ($posts as $item) {
            $slug = $item['slug'];
            unset($item['slug']);
            Post::query()->updateOrCreate(['slug' => $slug], $item);
        }
    }

    protected function seedFaqs(): void
    {
        $faqs = [
            [
                'question' => 'How do Software Development, DevOps, and DevSecOps fit together at C28?',
                'answer' => 'They form one connected offering. Many clients hire a developer to build a web application, only to get stuck figuring out how to containerize it, configure Linux/Nginx, automate deployments, and secure secrets. Because C28 bridges full-stack web development with cloud and DevOps engineering, we can build your product, automate its release pipeline, and maintain it in production.',
                'category' => 'general',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'We already have an application built. Can we hire C28 just for deployment & CI/CD?',
                'answer' => 'Yes—DevOps and Cloud Engineering is our primary specialization. If your developer or internal team has already written the application code, we can step in to containerize it with Docker, configure your production server and HTTPS domain, set up automated GitHub Actions pipelines, and implement basic health monitoring.',
                'category' => 'devops',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'What kind of DevSecOps security checks do you implement?',
                'answer' => 'We focus on practical, high-impact security integrated into your delivery workflow: removing hardcoded API keys and secrets from source code, separating development and production environment credentials, scanning dependencies and Docker container images for known vulnerabilities inside CI/CD, and hardening Nginx/HTTPS configurations.',
                'category' => 'devsecops',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'question' => 'Do we retain full ownership of our servers, cloud accounts, and code?',
                'answer' => '100%. Every Dockerfile, GitHub Actions workflow, Nginx configuration, and application repository lives directly in your organization\'s accounts. We also provide clear architecture documentation so your team understands how everything fits together.',
                'category' => 'ownership',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'question' => 'How do we start a project with C28?',
                'answer' => 'Book a short discovery consultation. We\'ll inspect your current repository or product requirements, identify the immediate deployment or development bottleneck, and propose a clear, fixed-scope sprint with concrete deliverables.',
                'category' => 'engagement',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $item) {
            $question = $item['question'];
            unset($item['question']);
            Faq::query()->updateOrCreate(['question' => $question], $item);
        }
    }

    protected function seedSiteSettings(): void
    {
        SiteSetting::setValue(
            'brand.name',
            'C28 — Software & Cloud Engineering',
            'brand',
            SiteSetting::TYPE_TEXT,
            'Authoritative studio business name'
        );

        SiteSetting::setValue(
            'brand.statement',
            'We build digital products and help teams deploy, secure, and operate them reliably.',
            'brand',
            SiteSetting::TYPE_TEXT,
            'Core brand positioning statement'
        );

        SiteSetting::setValue(
            'hero.badge',
            'C28 — SOFTWARE & CLOUD ENGINEERING',
            'hero',
            SiteSetting::TYPE_TEXT,
            'Top badge text in the hero section'
        );

        SiteSetting::setValue(
            'hero.headline',
            'We Build Digital Products & Help Teams Deploy, Secure, and Operate Them Reliably.',
            'hero',
            SiteSetting::TYPE_TEXT,
            'Primary hero H1 headline'
        );

        SiteSetting::setValue(
            'hero.subheadline',
            'Instead of hiring separate people for application code, server configuration, CI/CD automation, and security, partner with one connected engineering studio built for startup founders, small SaaS teams, and growing businesses.',
            'hero',
            SiteSetting::TYPE_TEXT,
            'Hero supporting paragraph'
        );

        SiteSetting::setValue(
            'hero.console_tabs',
            [
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
            'console',
            SiteSetting::TYPE_JSON,
            'Interactive hero control plane tabs and terminal logs'
        );

        SiteSetting::setValue(
            'blueprint.steps',
            $this->defaultBlueprintSteps(),
            'blueprint',
            SiteSetting::TYPE_JSON,
            '10-step production engineering blueprint sequence'
        );

        SiteSetting::setValue(
            'scenarios.items',
            [
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
                [
                    'code' => 'S3',
                    'title' => 'Security & Compliance Scenario',
                    'subtitle' => 'Pipeline Vulnerability Risk',
                    'quote' => 'We want to make sure API keys aren\'t leaked in git history and our dependencies and Docker images are checked for vulnerabilities before release.',
                    'solution' => 'DevSecOps secret management + automated Trivy & dependency scanning.',
                ],
                [
                    'code' => 'S4',
                    'title' => 'Growing Startup Scenario',
                    'subtitle' => 'Blind Production Operations',
                    'quote' => 'Our app is live on AWS, but we have no alerts if disk usage fills up or the server goes down—and our monthly cloud bill keeps creeping up.',
                    'solution' => 'Linux & uptime monitoring + infrastructure audit & cost optimization.',
                ],
                [
                    'code' => 'S5',
                    'title' => 'Product Builder Scenario',
                    'subtitle' => 'Full-Cycle Build + Cloud',
                    'quote' => 'We need a custom internal dashboard and backend API built from scratch, and we don\'t want to hire one agency for code and another for hosting.',
                    'solution' => 'End-to-end web application development + production cloud deployment.',
                ],
                [
                    'code' => 'S6',
                    'title' => 'Business Brand Scenario',
                    'subtitle' => 'Conversion & Technical SEO',
                    'quote' => 'Our company website looks outdated and loads slowly. We need a sharp redesign that communicates our value and performs well on search engines.',
                    'solution' => 'High-conversion website development, speed optimization & technical SEO.',
                ],
            ],
            'scenarios',
            SiteSetting::TYPE_JSON,
            'Six founder and engineering bottleneck scenarios'
        );

        SiteSetting::setValue(
            'contact.email',
            'engineering@c28studios.com',
            'contact',
            SiteSetting::TYPE_TEXT,
            'Primary studio inquiry email address'
        );

        SiteSetting::setValue(
            'social.github_url',
            'https://github.com/iam-borisjacobs/c28studios',
            'social',
            SiteSetting::TYPE_TEXT,
            'Official C28 GitHub repository URL'
        );

        SiteSetting::setValue(
            'cta.primary_label',
            'Book a Consultation',
            'cta',
            SiteSetting::TYPE_TEXT,
            'Primary header and hero call-to-action button label'
        );
    }

    /**
     * @return array<int, array<string, string>>
     */
    protected function defaultBlueprintSteps(): array
    {
        return [
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
            [
                'step' => '04',
                'short' => '4. Linux Server',
                'category' => 'INFRASTRUCTURE',
                'title' => 'Configure & Harden Production Linux Server',
                'deliverable' => 'Deliverable: Hardened Cloud VM / Server',
                'problem' => 'We provision your cloud instance, configure SSH key-only authentication, set up UFW firewall rules, and tune system swap and file descriptors.',
                'outcome' => 'Outcome: Stable, locked-down Linux host ready for container workloads.',
                'cmd' => '$ sudo ufw enable && sudo ufw status numbered',
            ],
            [
                'step' => '05',
                'short' => '5. Nginx & HTTPS',
                'category' => 'NETWORKING & SSL',
                'title' => 'Set Up Nginx Reverse Proxy & Automated HTTPS',
                'deliverable' => 'Deliverable: Domain Routing + TLS 1.3 Certificates',
                'problem' => 'We connect your custom domain, configure Nginx to route traffic to your running containers, and automate SSL certificate renewal.',
                'outcome' => 'Outcome: Fast, encrypted HTTPS traffic with security headers enabled.',
                'cmd' => '$ sudo nginx -t && sudo systemctl reload nginx',
            ],
            [
                'step' => '06',
                'short' => '6. CI/CD Pipeline',
                'category' => 'AUTOMATION',
                'title' => 'Implement Automated CI/CD Pipeline',
                'deliverable' => 'Deliverable: GitHub Actions Deployment Workflow',
                'problem' => 'Manual deployments waste engineering time and cause human error. We automate build, test, and deployment steps whenever code is pushed to GitHub.',
                'outcome' => 'Outcome: Push code to GitHub and watch your release deploy automatically.',
                'cmd' => '$ gh workflow run deploy-production.yml',
            ],
            [
                'step' => '07',
                'short' => '7. DevSecOps',
                'category' => 'SECURITY',
                'title' => 'Integrate DevSecOps Scanning & Secret Management',
                'deliverable' => 'Deliverable: Automated Vulnerability & Secret Checks',
                'problem' => 'We add dependency auditing, container image vulnerability scanning, and encrypted runtime secrets so credentials never sit in plain text.',
                'outcome' => 'Outcome: Vulnerable builds are caught before reaching production.',
                'cmd' => '$ trivy image --severity HIGH,CRITICAL c28/app:latest',
            ],
            [
                'step' => '08',
                'short' => '8. Monitoring',
                'category' => 'OBSERVABILITY',
                'title' => 'Configure Uptime, Log & Resource Monitoring',
                'deliverable' => 'Deliverable: Health Checks & Alerting',
                'problem' => 'You shouldn\'t find out your site is down from an angry customer. We monitor endpoint availability, container health, and Linux disk/CPU usage.',
                'outcome' => 'Outcome: Immediate visibility and alerts before small issues become outages.',
                'cmd' => '$ curl -fsS https://app.yourdomain.com/healthz',
            ],
            [
                'step' => '09',
                'short' => '9. Documentation',
                'category' => 'KNOWLEDGE HANDOFF',
                'title' => 'Document Architecture & Operational Runbooks',
                'deliverable' => 'Deliverable: Architecture Diagram & Runbook',
                'problem' => 'We document how your server, containers, secrets, and CI/CD pipeline work so your team is never locked into a black box.',
                'outcome' => 'Outcome: Any developer on your team can understand and operate the system.',
                'cmd' => '$ cat docs/PRODUCTION-ARCHITECTURE.md',
            ],
            [
                'step' => '10',
                'short' => '10. Live Ops',
                'category' => 'PRODUCTION SCALE',
                'title' => 'Operate, Optimize & Scale Reliably',
                'deliverable' => 'Deliverable: Reliable Production Operations',
                'problem' => 'With the foundation live, we support ongoing releases, cloud cost optimization, and performance tuning as your user base grows.',
                'outcome' => 'Outcome: Peace of mind so founders can focus on building product value.',
                'cmd' => '$ docker stats --no-stream',
            ],
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    protected function defaultCodeArtifacts(): array
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

