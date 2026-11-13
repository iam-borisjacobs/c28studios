# Phase 03 (Filament v3 `/admin` CMS Control Plane & Reusable Builder Block Library) — Implementation Report

## 1. Summary

Implemented the **FilamentPHP v3 `/admin` CMS Control Plane**, the reusable **C28 Builder / NioBuilder Block Library** (`app/Filament/Blocks/C28BuilderBlocks.php`), the **C28 Control Plane Overview Widget** (`app/Filament/Widgets/C28StatsOverview.php`), and all **6 Filament v3 Resources** (`ServiceResource`, `ProjectResource`, `PricingTierResource`, `PostResource`, `FaqResource`, `SiteSettingResource`) in accordance with the approved design review (`docs/c28-web/phase-03-admin-cms-and-builder/01-design-review.md`).

---

## 2. Approved Scope

1. Reusable `App\Filament\Blocks\C28BuilderBlocks` library implementing 7 `Builder\Block` schemas (`hero_terminal`, `architecture_step_list`, `code_artifact_viewer`, `metric_callout_grid`, `problem_solution_split`, `markdown_prose`, `cta_banner`) with collapsible, cloneable, and reorderable controls.
2. `App\Filament\Widgets\C28StatsOverview` dashboard widget registered in `AdminPanelProvider`.
3. Six Filament v3 Resources and their `List`, `Create`, and `Edit` page classes:
   - `ServiceResource` (`Studio Architecture` navigation group)
   - `ProjectResource` (`Engineering Content` navigation group)
   - `PricingTierResource` (`Studio Architecture` navigation group)
   - `PostResource` (`Engineering Content` navigation group)
   - `FaqResource` (`Site Configuration` navigation group)
   - `SiteSettingResource` (`Site Configuration` navigation group)
4. Feature & Livewire test suite (`tests/Feature/AdminCmsResourcesTest.php`).

---

## 3. Implementation Details

### 3.1 Reusable Builder Block Library (`app/Filament/Blocks/C28BuilderBlocks.php`)
- Implemented `C28BuilderBlocks::make(string $fieldName = 'builder_blocks', string $label = 'Dynamic Content Blocks'): Builder` returning a `Filament\Forms\Components\Builder` configured with `->collapsible()`, `->cloneable()`, `->reorderableWithButtons()`, and `->blockNumbers(false)`.
- Defined 7 structured `Builder\Block` schemas in `C28BuilderBlocks::blocks()`:
  1. `hero_terminal` — Terminal window title, SSH target, status badge, repeater of terminal log lines (`timestamp`, `message`, `status`), and outcome summary.
  2. `architecture_step_list` — Section heading and repeater of architecture steps (`step`, `category`, `title`, `deliverable`, `problem`, `outcome`, `cmd`).
  3. `code_artifact_viewer` — Artifact filename/title, syntax language selector (`yaml`, `dockerfile`, `nginx`, `bash`, `php`, `javascript`, `json`), monospace code textarea, and caption.
  4. `metric_callout_grid` — Grid heading and repeater of metrics (`value`, `label`, `note`).
  5. `problem_solution_split` — Badge, problem heading/description, solution heading/description, and optional verification command.
  6. `markdown_prose` — Section heading and prose/markdown content textarea.
  7. `cta_banner` — Badge, banner heading, supporting copy, CTA button label, and target URL/anchor.

### 3.2 Control Plane Dashboard Widget (`app/Filament/Widgets/C28StatsOverview.php`)
- Created `C28StatsOverview` extending `Filament\Widgets\StatsOverviewWidget` and registered it in `app/Providers/Filament/AdminPanelProvider.php`.
- Displays 4 live stats cards:
  1. **Active Services** (`4-Tier Hierarchy: DevOps -> DevSecOps -> Software -> Web`)
  2. **Published Projects** (with dynamic breakdown of `Client Production` vs `Lab Demonstrations`)
  3. **Active Engagement Tiers** (with dynamic breakdown of `Fixed Sprints` vs `Monthly Retainers`)
  4. **Published Lab Notes & Posts** (`Across 5 C28 Engineering Categories`)

### 3.3 Six Filament v3 Resources (`app/Filament/Resources/`)
1. **`ServiceResource`**:
   - Grouped under `Studio Architecture` (sort `1`) with active count badge.
   - Auto-generates `slug` from `title` on create; includes `tier` (`Service::TIERS`), `tier_badge`, `icon`, `sort_order`, `is_featured`, `is_active`, `short_outcome`, `description`, `deliverables` (`TagsInput`), and `C28BuilderBlocks::make('builder_blocks')`.
   - Table supports `->reorderable('sort_order')`, `projects_count`, inline `ToggleColumn` switches for `is_featured` and `is_active`, and filters for `tier` and `is_active`.
2. **`ProjectResource`**:
   - Grouped under `Engineering Content` (sort `1`) with published count badge.
   - 4-tab layout (`Overview & Work Classification`, `Metrics, Stack & Publishing`, `Architecture Steps & Code Artifacts`, `Visual Builder Blocks`).
   - Enforces the mandatory `AGENTS.md` `work_type` classification (`client_production` vs `lab_demonstration`) with badge color coding (`success` vs `info`).
3. **`PricingTierResource`**:
   - Grouped under `Studio Architecture` (sort `2`) with active count badge.
   - Supports `billing_mode` (`sprint_fixed`, `monthly_retainer`, `custom`), `price_amount`, `price_label`, `billing_period`, `target_audience`, `features_heading`, `features` (`TagsInput`), `cta_label`, `cta_url`, `is_highlighted`, `is_active`, and `->reorderable('sort_order')`.
4. **`PostResource`**:
   - Grouped under `Engineering Content` (sort `2`) with published count badge.
   - Supports all 5 C28 categories (`Post::CATEGORIES`), `work_type` (`Post::WORK_TYPES`), `hook_text`, `excerpt`, `reading_time_minutes`, `video_url`, `code_snippets` (`Repeater`), and `C28BuilderBlocks::make('content_blocks', 'Article Content Blocks')`.
5. **`FaqResource`**:
   - Grouped under `Site Configuration` (sort `1`) with active count badge and `->reorderable('sort_order')`.
6. **`SiteSettingResource`**:
   - Grouped under `Site Configuration` (sort `2`).
   - Dynamically switches form input based on `type`: scalar `value` textarea for `text`/`boolean`, and a pretty-printed, JSON-validated monospace `json_value` editor (`json_encode(..., JSON_PRETTY_PRINT)` on load, `json_decode(..., true)` on save) for `type === 'json'`, ensuring nested arrays (`hero.console_tabs`, `blueprint.steps`, `scenarios.items`) are never flattened or corrupted.

---

## 4. Files Created or Modified

### Created
- `app/Filament/Blocks/C28BuilderBlocks.php`
- `app/Filament/Widgets/C28StatsOverview.php`
- `app/Filament/Resources/ServiceResource.php`
- `app/Filament/Resources/ServiceResource/Pages/ListServices.php`
- `app/Filament/Resources/ServiceResource/Pages/CreateService.php`
- `app/Filament/Resources/ServiceResource/Pages/EditService.php`
- `app/Filament/Resources/ProjectResource.php`
- `app/Filament/Resources/ProjectResource/Pages/ListProjects.php`
- `app/Filament/Resources/ProjectResource/Pages/CreateProject.php`
- `app/Filament/Resources/ProjectResource/Pages/EditProject.php`
- `app/Filament/Resources/PricingTierResource.php`
- `app/Filament/Resources/PricingTierResource/Pages/ListPricingTiers.php`
- `app/Filament/Resources/PricingTierResource/Pages/CreatePricingTier.php`
- `app/Filament/Resources/PricingTierResource/Pages/EditPricingTier.php`
- `app/Filament/Resources/PostResource.php`
- `app/Filament/Resources/PostResource/Pages/ListPosts.php`
- `app/Filament/Resources/PostResource/Pages/CreatePost.php`
- `app/Filament/Resources/PostResource/Pages/EditPost.php`
- `app/Filament/Resources/FaqResource.php`
- `app/Filament/Resources/FaqResource/Pages/ListFaqs.php`
- `app/Filament/Resources/FaqResource/Pages/CreateFaq.php`
- `app/Filament/Resources/FaqResource/Pages/EditFaq.php`
- `app/Filament/Resources/SiteSettingResource.php`
- `app/Filament/Resources/SiteSettingResource/Pages/ListSiteSettings.php`
- `app/Filament/Resources/SiteSettingResource/Pages/CreateSiteSetting.php`
- `app/Filament/Resources/SiteSettingResource/Pages/EditSiteSetting.php`
- `tests/Feature/AdminCmsResourcesTest.php`
- `docs/c28-web/phase-03-admin-cms-and-builder/01-design-review.md`
- `docs/c28-web/phase-03-admin-cms-and-builder/02-implementation.md`

### Modified
- `app/Providers/Filament/AdminPanelProvider.php` — Registered `C28StatsOverview::class` in panel widgets.

---

## 5. Security and Authorization

- All 6 Filament Resources are mounted under `/admin` and protected by `Authenticate` middleware and `User::canAccessPanel(Panel $panel): bool` (`(bool) $this->is_admin`).
- Server-side validation rules enforce required fields, unique slugs, valid enum options (`Service::TIERS`, `Project::WORK_TYPES`, `PricingTier::BILLING_MODES`, `Post::CATEGORIES`), and valid JSON syntax on `SiteSettingResource` when `type === 'json'`.

---

## 6. Data/Storage Changes

- Zero schema migrations required; operates directly on the 6 domain tables created in Phase 02.

---

## 7. Compatibility and Regression Considerations

- `SiteSettingResource` uses custom `formatStateUsing` and `dehydrateStateUsing` callbacks for `json_value` so multi-level nested JSON settings (`hero.console_tabs`, `blueprint.steps`, `scenarios.items`) round-trip cleanly without data loss.
- All Phase 01 (`FoundationTest`) and Phase 02 (`DomainSchemaTest`) tests remain intact.

---

## 8. Deferred Work and Known Limitations

- **Phase 04 Scope**: Connecting the public Blade frontend (`GET /`, `GET /projects/{project:slug}`, `GET /insights/{post:slug}`) and Blade Builder block components (`resources/views/components/blocks/*.blade.php`) to render these CMS-managed records is designed in `docs/c28-web/phase-04-dynamic-public-frontend/01-design-review.md`.

