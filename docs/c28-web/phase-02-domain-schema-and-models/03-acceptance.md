# Phase 02 (Domain Schema, Eloquent Models, Factories & C28 Seeders) — Acceptance Report

## 1. Acceptance Criteria

| # | Criterion | Status | Evidence / File Reference |
| :--- | :--- | :--- | :--- |
| 1 | `services` migration, `Service` model, and `ServiceFactory` supporting 4-tier hierarchy (`primary`, `secondary`, `supporting`), `deliverables`, and `builder_blocks` JSON | **Pass** | `2026_11_13_000001_create_services_table.php`, `app/Models/Service.php`, `database/factories/ServiceFactory.php`; verified by `DomainSchemaTest::test_services_model_supports_four_tier_hierarchy_and_json_casts`. |
| 2 | `projects` migration, `Project` model, and `ProjectFactory` enforcing `work_type` (`client_production` vs `lab_demonstration`), `service_id` FK (`nullOnDelete`), `outcome_metrics`, `tech_stack`, `architecture_steps`, `code_artifacts`, and `builder_blocks` JSON | **Pass** | `2026_11_13_000002_create_projects_table.php`, `app/Models/Project.php`, `database/factories/ProjectFactory.php`; verified by `DomainSchemaTest::test_projects_model_enforces_work_type_distinction_and_service_relationship`. |
| 3 | `pricing_tiers` migration, `PricingTier` model, and `PricingTierFactory` supporting `billing_mode` (`sprint_fixed`, `monthly_retainer`, `custom`), `price_amount`, `price_label`, and `features` JSON | **Pass** | `2026_11_13_000003_create_pricing_tiers_table.php`, `app/Models/PricingTier.php`, `database/factories/PricingTierFactory.php`; verified by `DomainSchemaTest::test_pricing_tiers_support_billing_modes_and_features_json`. |
| 4 | `posts` migration, `Post` model, and `PostFactory` supporting all 5 C28 categories (`devops_demo`, `developer_conversation`, `web_software_dev`, `devsecops_security`, `engineering_journey`), `work_type`, `content_blocks`, and `code_snippets` JSON | **Pass** | `2026_11_13_000004_create_posts_table.php`, `app/Models/Post.php`, `database/factories/PostFactory.php`; verified by `DomainSchemaTest::test_posts_support_five_c28_categories_and_work_type_distinction`. |
| 5 | `faqs` & `site_settings` migrations, `Faq` & `SiteSetting` models (`getValue` / `setValue`), and factories | **Pass** | `2026_11_13_000005_create_faqs_table.php`, `2026_11_13_000006_create_site_settings_table.php`, `app/Models/Faq.php`, `app/Models/SiteSetting.php`; verified by `DomainSchemaTest::test_faqs_and_site_settings_helpers_work_reliably`. |
| 6 | Idempotent `C28ContentSeeder` registered in `DatabaseSeeder` populating 4 services, 3 projects, 6 pricing tiers, 5 posts, 5 FAQs, and 11 site settings | **Pass** | `database/seeders/C28ContentSeeder.php`, `database/seeders/DatabaseSeeder.php`; verified by `DomainSchemaTest::test_c28_content_seeder_populates_all_domain_tables_and_is_idempotent`. |

---

## 2. Automated Verification

| Command | Result | Evidence / Notes |
| :--- | :--- | :--- |
| `php artisan test --ansi` | **Pass (Exit 0)** | **13 passed (52 assertions)** in `2.34s` (`Tests\Unit\ExampleTest` [1], `Tests\Feature\ExampleTest` [1], `Tests\Feature\FoundationTest` [5], and `Tests\Feature\DomainSchemaTest` [6]). |

### Observed `DomainSchemaTest` Breakdown
- `services model supports four tier hierarchy and json casts`: **PASS** (`0.72s`)
- `projects model enforces work type distinction and service relationship`: **PASS** (`0.05s`)
- `pricing tiers support billing modes and features json`: **PASS** (`0.04s`)
- `posts support five c28 categories and work type distinction`: **PASS** (`0.04s`)
- `faqs and site settings helpers work reliably`: **PASS** (`0.04s`)
- `c28 content seeder populates all domain tables and is idempotent`: **PASS** (`0.10s`)

---

## 3. Manual / Browser Verification

- Phase 02 is a backend database schema, model, factory, and seeder phase. All model behaviors, foreign key constraints (`nullOnDelete`), JSON casts, and seeder idempotency were verified via automated database integration tests (`RefreshDatabase`).

---

## 4. Build, Static Analysis, and Framework Checks

- **Database Migrations & Seeding**:
  - Verified clean execution of all 9 migrations (`users`, `cache`, `jobs`, `services`, `projects`, `pricing_tiers`, `posts`, `faqs`, `site_settings`) and `DatabaseSeeder` (`C28ContentSeeder`) during `RefreshDatabase` test runs.
  - Note on local MySQL (`c28studios`): As observed in Phase 01, local `MySQL80` on `127.0.0.1:3306` requires a password for `root@localhost` (`SQLSTATE[HY000] [1045] Access denied for user 'root'@'localhost' (using password: NO)`). Once `DB_PASSWORD` is set in local `.env`, `php artisan migrate --seed` will apply all 9 migrations and `C28ContentSeeder` to MySQL.

---

## 5. Documentation

- `docs/c28-web/phase-02-domain-schema-and-models/01-design-review.md` — Stage A Design Review.
- `docs/c28-web/phase-02-domain-schema-and-models/02-implementation.md` — Stage D Implementation Report.
- `docs/c28-web/phase-02-domain-schema-and-models/03-acceptance.md` — Stage D Acceptance Report.

---

## 6. Known Limitations and Deferred Work

- **FilamentPHP v3 `/admin` Resources & Builder Blocks**: Scheduled for Phase 03 and designed in `docs/c28-web/phase-03-admin-cms-and-builder/01-design-review.md`.

---

## 7. Git Delivery

- **Status**: Not performed (awaiting explicit user authorization for Stage E).

---

## 8. Final Status

**Verified & Accepted**

