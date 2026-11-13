# AGENTS.md — C28 Project Constitution & Engineering Source of Truth

This file is the authoritative constitution for all human and AI engineering work in the **C28 — Software & Cloud Engineering** repository (`c28studios`). Every feature, design review, database schema, CMS block, and frontend view must adhere strictly to the rules below.

---

## 1. Identity & Source of Truth

- **Business Name**: C28 — Software & Cloud Engineering
- **Core Statement**: *"We build digital products and help teams deploy, secure, and operate them reliably."*
- **Positioning**: C28 is a connected software and cloud engineering studio built for startup founders, small SaaS teams, and growing businesses. Instead of forcing clients to hire separate contractors for application code, Linux/Docker server configuration, CI/CD automation, and security, C28 delivers one cohesive engineering partnership from local codebase to hardened production operations.

---

## 2. Strict 4-Tier Service Hierarchy

All site navigation, service pages, CMS models, pricing/engagement tiers, and copy must preserve this exact service hierarchy:

1. **Primary — DevOps & Cloud Engineering (`01 • Primary Service`)**
   - Application deployment, Linux server configuration & hardening, CI/CD pipelines (GitHub Actions), Docker containerization, cloud infrastructure setup, Nginx reverse proxy & TLS/HTTPS, operational health/resource monitoring, and cloud cost control.
2. **Secondary — DevSecOps Integration (`02 • Secondary Service`)**
   - Dependency vulnerability scanning, secret management & dev/production credential isolation, Docker container image scanning (e.g., Trivy), and automated CI/CD security gates.
3. **Supporting — Software Development (`03 • Supporting Service`)**
   - Multi-tenant SaaS applications, REST/GraphQL APIs, backend systems, business web applications, and custom internal dashboards engineered from day one for clean deployment and maintainability.
4. **Supporting — Website Development (`04 • Supporting Service`)**
   - High-conversion business websites, landing pages, e-commerce platforms, website redesigns, performance optimization, and technical SEO.

---

## 3. Content Categories & Mandatory Integrity Rule

### 3.1 Five Supported Content Categories
All articles, walkthroughs, case studies, and lab notes must map to one of these five categories:
1. **Practical DevOps demonstrations** (`devops-demonstration`)
2. **Developer conversations** (`developer-conversations`)
3. **Website/software development** (`software-web-development`)
4. **DevSecOps/security** (`devsecops-security`)
5. **Personal engineering journey** (`engineering-journey`)

### 3.2 Mandatory Distinction Rule (Client Production vs. Lab Projects)
- Every project, case study, architectural walkthrough, or portfolio entry **MUST** explicitly distinguish between:
  - **Production Client Work** (`work_type = client_production`)
  - **Personal / Studio Lab Projects** (`work_type = lab_demonstration`)
- **Zero Fabrication Policy**: Never invent fake client names, fake testimonials, unverified uptime percentages presented as client case studies, or unauthorized company logos. Demonstrate competence through real engineering walkthroughs, verifiable configurations, and transparent lab demonstrations.

---

## 4. Locked Tech Stack & Architectural Boundaries

- **Backend Framework**: Laravel 11 (PHP 8.2+)
- **Database**:
  - Primary / Production / Local Development: MySQL 8.0+ (`DB_CONNECTION=mysql`, `DB_DATABASE=c28studios`)
  - Automated Testing (`phpunit.xml`): Isolated SQLite (`:memory:`) or dedicated test schema so `php artisan test` runs deterministically without mutating local development data.
- **Admin CMS / Control Plane**:
  - FilamentPHP v3 (`/admin`) powered by `App\Providers\Filament\AdminPanelProvider`.
  - Dynamic Content & Page Building: Filament Builder (`Filament\Forms\Components\Builder` / `Builder\Block`) and NioBuilder-aligned JSON block schemas stored in `app/Filament/Blocks/` and rendered via Blade components in `resources/views/components/blocks/` so the studio owner can dynamically add and edit projects, services, pricing tiers, case studies, and site content from `/admin`.
- **Public Frontend Stack**:
  - Laravel Blade + Alpine.js + Vanilla JS + Tailwind CSS v3.4 compiled via Vite (`resources/css/app.css`, `resources/js/app.js`).
  - **Script Isolation Rule**: Public layouts (`resources/views/layouts/app.blade.php`) load `@vite(['resources/css/app.css', 'resources/js/app.js'])`. Never inject the public `resources/js/app.js` bundle into Filament's `/admin` panel, as Filament v3 manages its own Livewire v3 + Alpine runtime.
  - Do not introduce React, Vue, Inertia, or public-facing Livewire components unless an explicit architectural exception is approved by the user.

---

## 5. Visual System & Aesthetic Rules

- **Aesthetic Direction**: Bespoke, high-craft dark engineering aesthetic inspired by terminal control planes, deployment pipelines, and real infrastructure telemetry.
- **Color Tokens**:
  - **Deep Obsidian (Background Primary)**: `#050806` (`obsidian-900`) and `#040705` (`obsidian-950`)
  - **Carbon (Surface / Cards)**: `#0A100D` (`obsidian-800`), `#070c09` (`obsidian-850`), `#101f16` (`obsidian-700`)
  - **Glowing Emerald (Primary Accent)**: `#10B981` (`emerald-500`), `#00E676` (`emerald-accent`), `#22c55e` (`neon-500`), `#4ade80` (`neon-400`)
  - **Text Hierarchy**: `#F1F5F9` (`slate-100` headings/primary), `#CBD5E1` (`slate-300` body), `#94A3B8` (`slate-400` muted metadata)
- **Typography**:
  - **UI & Headings**: `Plus Jakarta Sans` (`font-sans`)
  - **Code, Terminal Logs, Badges & Telemetry**: `JetBrains Mono` (`font-mono`)
- **Authentic Engineering Artifacts**:
  - Visual sections must feature real, syntactically valid engineering artifacts (`Dockerfile`, `nginx.conf`, `.github/workflows/deploy-production.yml`, Trivy scan output, Linux health checks) rather than generic corporate illustrations.

---

## 6. Security, Authorization, Validation & Quality Gates

1. **Admin Panel Authorization (`FilamentUser`)**:
   - `App\Models\User` must implement `Filament\Models\Contracts\FilamentUser`.
   - `canAccessPanel(Panel $panel): bool` must enforce `(bool) $this->is_admin` across **all** environments (`local`, `testing`, `staging`, `production`). Non-admin users (`is_admin = false`) must receive `403 Forbidden`.
2. **Server-Side Validation & CSRF**:
   - All public form submissions (consultation inquiries, quote requests) must validate inputs via dedicated Laravel `FormRequest` classes, enforce CSRF protection, and apply rate limiting (`throttle`).
3. **Output Encoding**:
   - Always use escaped Blade output (`{{ $value }}`). Never render untrusted or user-submitted content with `{!! $value !!}`.
4. **Secret Hygiene**:
   - Never commit `.env` files, API keys, private SSH keys, or database passwords to Git. `.env.example` must contain safe placeholder values only.

---

## 7. Engineering Phase Orchestrator Mandate

All work in this repository must follow `.agent/skills/engineering-phase-orchestrator/SKILL.md`:

1. **Stage 0 — Discovery**: Inspect repository state, `git status`, existing files, and environment before proposing or changing anything. Preserve existing user files (such as `c28_landing_page.html` and `.agent/`).
2. **Stage A — Design Review (Hard Stop)**: Write `docs/c28-web/phase-XX-<slug>/01-design-review.md` and stop for explicit user approval before modifying application code.
3. **Stage B — Approved Implementation**: Implement strictly within the approved Phase scope. Stop and request re-approval if material scope changes arise.
4. **Stage C — Verification**: Execute `php artisan test`, `npm run build`, and relevant route/schema checks. Report only commands actually run and outcomes actually observed.
5. **Stage D — Documentation & Acceptance**: Produce `02-implementation.md` and `03-acceptance.md` inside `docs/c28-web/phase-XX-<slug>/`.
6. **Stage E — Git Delivery**: Stage, commit, or push **only** when explicitly authorized by the user.
