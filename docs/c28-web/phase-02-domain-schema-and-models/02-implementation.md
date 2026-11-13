# Phase 02 (Domain Schema, Eloquent Models, Factories & C28 Seeders) — Implementation Report

## 1. Summary

Implemented and verified the Phase 02 domain database schema, Eloquent models, model factories, and idempotent C28 content seeder in accordance with the approved design review (`docs/c28-web/phase-02-domain-schema-and-models/01-design-review.md`):
- Created 6 migrations (`services`, `projects`, `pricing_tiers`, `posts`, `faqs`, `site_settings`) with full MySQL 8.0+ and SQLite compatibility.
- Created 6 Eloquent models (`Service`, `Project`, `PricingTier`, `Post`, `Faq`, `SiteSetting`) with strict domain constants, `$fillable`, typed `casts()`, Eloquent relationships (`Service::projects()`, `Project::service()`), mandatory `work_type` (`client_production` vs `lab_demonstration`) and 5-category helpers, `SiteSetting::getValue()` / `SiteSetting::setValue()` helpers, and query scopes (`active()`, `published()`, `featured()`, `ordered()`, `forBillingMode()`, `byCategory()`).
- Created 6 model factories (`ServiceFactory`, `ProjectFactory`, `PricingTierFactory`, `PostFactory`, `FaqFactory`, `SiteSettingFactory`).
- Created `database/seeders/C28ContentSeeder.php` and registered it in `database/seeders/DatabaseSeeder.php` to idempotently seed the 4 C28 services, 3 reference projects/case studies (with 10-step `architecture_steps` and real `Dockerfile`, `nginx.conf`, and GitHub Actions YAML `code_artifacts`), 6 pricing tiers (`sprint_fixed` and `monthly_retainer`), 5 engineering posts across all 5 categories, 5 founder FAQs, and 10+ `site_settings` records.
- Created and executed `tests/Feature/DomainSchemaTest.php` — all **13 tests (52 assertions)** across the test suite pass in `2.34s`.

---

## 2. Approved Scope

1. Six migrations (`services`, `projects`, `pricing_tiers`, `posts`, `faqs`, `site_settings`).
2. Six Eloquent models (`App\Models\Service`, `App\Models\Project`, `App\Models\PricingTier`, `App\Models\Post`, `App\Models\Faq`, `App\Models\SiteSetting`).
3. Six model factories (`ServiceFactory`, `ProjectFactory`, `PricingTierFactory`, `PostFactory`, `FaqFactory`, `SiteSettingFactory`).
4. Idempotent `C28ContentSeeder` registered in `DatabaseSeeder`.
5. Feature test suite (`tests/Feature/DomainSchemaTest.php`).

---

## 3. Implementation Details

### 3.1 Migrations (`database/migrations/2026_11_13_000001_*` through `000006_*`)
- `2026_11_13_000001_create_services_table.php`: `tier` (`primary`, `secondary`, `supporting`), `tier_badge`, `title`, `slug` (unique), `short_outcome`, `description`, `icon`, `deliverables` (nullable JSON), `builder_blocks` (nullable JSON), `sort_order`, `is_featured`, `is_active`.
- `2026_11_13_000002_create_projects_table.php`: `service_id` (nullable FK to `services` with `nullOnDelete()`), `title`, `slug` (unique), `work_type` (`client_production`, `lab_demonstration`), `client_or_context`, `problem_summary`, `solution_summary`, `outcome_metrics` (JSON), `tech_stack` (JSON), `architecture_steps` (JSON), `code_artifacts` (JSON), `builder_blocks` (JSON), `repo_url`, `live_url`, `featured_image`, `is_featured`, `is_published`, `published_at`, `sort_order`.
- `2026_11_13_000003_create_pricing_tiers_table.php`: `name`, `slug` (unique), `tier_badge`, `billing_mode` (`sprint_fixed`, `monthly_retainer`, `custom`), `price_amount` (nullable unsigned integer), `price_label`, `billing_period`, `target_audience`, `features_heading`, `features` (JSON), `cta_label`, `cta_url`, `is_highlighted`, `is_active`, `sort_order`.
- `2026_11_13_000004_create_posts_table.php`: `title`, `slug` (unique), `category` (`devops_demo`, `developer_conversation`, `web_software_dev`, `devsecops_security`, `engineering_journey`), `work_type` (`client_production`, `lab_demonstration`), `hook_text`, `excerpt`, `content_blocks` (JSON), `code_snippets` (JSON), `video_url`, `reading_time_minutes`, `is_published`, `published_at`.
- `2026_11_13_000005_create_faqs_table.php`: `question`, `answer`, `category`, `sort_order`, `is_active`.
- `2026_11_13_000006_create_site_settings_table.php`: `key` (unique), `group`, `type` (`text`, `json`, `boolean`), `value`, `json_value` (JSON), `description`.

### 3.2 Eloquent Models (`app/Models/`)
- `Service`: Defines `TIER_PRIMARY`, `TIER_SECONDARY`, `TIER_SUPPORTING`, `TIERS`, `projects()` HasMany relation, `tierLabel()`, and `active()`, `featured()`, `ordered()` scopes.
- `Project`: Defines `WORK_TYPE_CLIENT_PRODUCTION`, `WORK_TYPE_LAB_DEMONSTRATION`, `WORK_TYPES`, `service()` BelongsTo relation, `isClientProduction()`, `isLabDemonstration()`, `workTypeLabel()`, and `published()`, `featured()`, `ordered()` scopes.
- `PricingTier`: Defines `MODE_SPRINT_FIXED`, `MODE_MONTHLY_RETAINER`, `MODE_CUSTOM`, `BILLING_MODES`, `billingModeLabel()`, and `active()`, `forBillingMode()`, `ordered()` scopes.
- `Post`: Defines all 5 `CATEGORY_*` constants (`CATEGORIES`) and `WORK_TYPES`, `isClientProduction()`, `isLabDemonstration()`, `categoryLabel()`, `workTypeLabel()`, and `published()`, `byCategory()`, `ordered()` scopes.
- `Faq`: Defines `active()` and `ordered()` scopes.
- `SiteSetting`: Defines `TYPE_TEXT`, `TYPE_JSON`, `TYPE_BOOLEAN`, `SiteSetting::getValue(string $key, mixed $default = null)`, and `SiteSetting::setValue(...)`.

### 3.3 Factories & `C28ContentSeeder`
- Created factories for all 6 models (`ServiceFactory`, `ProjectFactory` with `clientProduction()` and `labDemonstration()` states, `PricingTierFactory`, `PostFactory`, `FaqFactory`, `SiteSettingFactory`).
- Created `database/seeders/C28ContentSeeder.php` using `updateOrCreate` to seed:
  - 4 services (`devops-cloud-engineering`, `devsecops-integration`, `software-development`, `website-development`)
  - 3 projects (`zero-downtime-docker-nginx-cicd-reference`, `devsecops-container-vulnerability-and-secret-gate`, `multi-tenant-saas-application-and-cloud-deployment`)
  - 6 pricing tiers (3 `sprint_fixed` + 3 `monthly_retainer`)
  - 5 engineering posts (one in each of the 5 C28 categories)
  - 5 founder FAQs
  - 11 `site_settings` keys (`brand.name`, `brand.statement`, `hero.badge`, `hero.headline`, `hero.subheadline`, `hero.console_tabs`, `blueprint.steps`, `scenarios.items`, `contact.email`, `social.github_url`, `cta.primary_label`)

---

## 4. Files Created or Modified

### Created
- `database/migrations/2026_11_13_000001_create_services_table.php`
- `database/migrations/2026_11_13_000002_create_projects_table.php`
- `database/migrations/2026_11_13_000003_create_pricing_tiers_table.php`
- `database/migrations/2026_11_13_000004_create_posts_table.php`
- `database/migrations/2026_11_13_000005_create_faqs_table.php`
- `database/migrations/2026_11_13_000006_create_site_settings_table.php`
- `app/Models/Service.php`
- `app/Models/Project.php`
- `app/Models/PricingTier.php`
- `app/Models/Post.php`
- `app/Models/Faq.php`
- `app/Models/SiteSetting.php`
- `database/factories/ServiceFactory.php`
- `database/factories/ProjectFactory.php`
- `database/factories/PricingTierFactory.php`
- `database/factories/PostFactory.php`
- `database/factories/FaqFactory.php`
- `database/factories/SiteSettingFactory.php`
- `database/seeders/C28ContentSeeder.php`
- `tests/Feature/DomainSchemaTest.php`
- `docs/c28-web/phase-02-domain-schema-and-models/01-design-review.md`
- `docs/c28-web/phase-02-domain-schema-and-models/02-implementation.md`
- `docs/c28-web/phase-02-domain-schema-and-models/03-acceptance.md`

### Modified
- `database/seeders/DatabaseSeeder.php` — Added `$this->call(C28ContentSeeder::class);`.

---

## 5. Security and Authorization

- Enforced `AGENTS.md` Section 3.2 integrity rules via explicit `work_type` (`client_production` vs `lab_demonstration`) and `category` constants on `Project` and `Post`.
- All models define explicit `$fillable` allowlists and typed `casts()` arrays.

---

## 6. Data/Storage Changes

- Added 6 domain tables (`services`, `projects`, `pricing_tiers`, `posts`, `faqs`, `site_settings`) with indexed lookup columns (`slug`, `tier`, `work_type`, `billing_mode`, `category`, `sort_order`, `is_active`, `is_published`, `is_featured`, `key`, `group`).

---

## 7. Compatibility and Regression Considerations

- All JSON and TEXT columns are `->nullable()` in migrations to guarantee strict-mode compatibility with MySQL 8.0+ as well as SQLite in-memory testing.
- `projects.service_id` uses `->nullOnDelete()` so deleting a service never cascades to delete historical case studies or lab demonstrations.
- All Phase 01 tests (`FoundationTest`) continue to pass alongside `DomainSchemaTest`.

---

## 8. Deferred Work and Known Limitations

- **Phase 03 Scope**: FilamentPHP v3 `/admin` CRUD resources (`ServiceResource`, `ProjectResource`, `PricingTierResource`, `PostResource`, `FaqResource`, `SiteSettingResource`), `C28StatsOverview` dashboard widget, and `C28BuilderBlocks` library are designed in `docs/c28-web/phase-03-admin-cms-and-builder/01-design-review.md` and awaiting Stage A approval.

