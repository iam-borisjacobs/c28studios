# Phase 05 — Design Review: Lead Capture, Consultation Inquiries & Admin Pipeline Management

**Project**: C28 — Software & Cloud Engineering (`c28studios`)
**Phase**: `phase-05-lead-capture-and-inquiries`
**Stage**: Stage A — Design Review (Approval Gate)
**Date**: 2026-11-13
**Status**: **AWAITING USER APPROVAL (HARD STOP)**

---

## 1. Objective & Scope

Phase 05 completes the lead capture and inquiry pipeline for **C28 — Software & Cloud Engineering**, connecting the public consultation modal and `#consultation` architecture scoping section to a validated, CSRF-protected, rate-limited, honeypot-guarded backend endpoint (`POST /inquiries`) and a dedicated Filament v3 resource (`InquiryResource`) inside `/admin`.

### In-Scope Deliverables
1. **Database Migration (`inquiries`) & Eloquent Model (`App\Models\Inquiry`) + Factory (`InquiryFactory`)**:
   - Captures contact identity (`name`, `email`, `company`), engineering scope (`service_interest`, `engagement_mode`, `project_type`, `budget_range`, `timeline`, `message`), operational workflow state (`status`: `new`, `in_review`, `responded`, `archived`), internal studio notes (`admin_notes`), and security telemetry (`ip_address`, `user_agent`).
2. **Server-Side Validation, Security & Rate Limiting (`POST /inquiries`)**:
   - Dedicated `App\Http\Requests\StoreInquiryRequest` (`FormRequest`) enforcing strict validation rules, input sanitization, and honeypot rejection (`website_url`).
   - `App\Http\Controllers\Public\InquiryController@store` protected by CSRF and `throttle:5,1` middleware, supporting both standard form redirects (`#consultation` with flash confirmation) and JSON/AJAX modal submissions (`201 Created`).
3. **Public Consultation Section (`#consultation`) & Interactive Modal Integration**:
   - Dedicated `#consultation` section on `resources/views/welcome.blade.php` and upgraded `#consultationModal` in `resources/views/layouts/app.blade.php` + `resources/js/app.js` pre-selecting the relevant C28 service tier or sprint/retainer mode when clicked from any Service or Pricing card.
4. **Filament v3 Admin Lead Pipeline (`InquiryResource` + `C28StatsOverview` Update)**:
   - `App\Filament\Resources\InquiryResource` under the `Lead & Client Pipeline` navigation group with unread `new` badge count in the sidebar, status color badges, filterable table, and internal `admin_notes` editor.
   - Update `C28StatsOverview` widget to include a 5th live stat card displaying unread (`new`) vs. total consultation inquiries.
5. **Automated Feature Test Suite (`tests/Feature/LeadCaptureInquiryTest.php`)**:
   - End-to-end tests covering valid submissions, validation failures, honeypot bot rejection, rate limiting (`throttle:5,1`), and Filament `/admin/inquiries` management.

---

## 2. Stage 0 Discovery & Current State Audit

| Component | Current State (Post-Phase 04) | Phase 05 Requirement |
| :--- | :--- | :--- |
| Public Consultation Modal (`#consultationModal`) | Renders in `resources/views/layouts/app.blade.php` with client-side UI state (`c28OpenModal`, `c28SubmitModal`) | Connect to `POST /inquiries` with CSRF token, honeypot field (`website_url`), server-side validation error display, and database persistence |
| Public Homepage (`#consultation` anchor) | Pricing & CTA links reference `#consultation` or trigger `c28OpenModal(...)` | Add a dedicated on-page `#consultation` Architecture Scoping & Lead Capture section above the footer in `welcome.blade.php` in addition to the quick-launch modal |
| `inquiries` Table & `Inquiry` Model | Not yet created | Create migration `2026_11_13_000007_create_inquiries_table.php`, `App\Models\Inquiry`, and `Database\Factories\InquiryFactory` |
| Filament `/admin` Lead Management | 6 content resources (`Service`, `Project`, `PricingTier`, `Post`, `Faq`, `SiteSetting`) | Add `InquiryResource` in `Lead & Client Pipeline` group with navigation badge count for `status = 'new'` and update `C28StatsOverview` |

---

## 3. Architecture & Data Model Design

### 3.1 Database Schema: `inquiries` (`2026_11_13_000007_create_inquiries_table.php`)

| Column | Type | Modifiers | Purpose |
| :--- | :--- | :--- | :--- |
| `id` | `bigint unsigned` | PK, Auto-increment | Primary key |
| `name` | `string(120)` | Required | Prospect full name and role |
| `email` | `string(190)` | Required, Indexed | Prospect work email |
| `company` | `string(150)` | Nullable | Organization, startup, or project name |
| `service_interest` | `string(120)` | Required, Indexed | Selected C28 service tier (`DevOps & Cloud Engineering`, `DevSecOps Integration`, `Software Development`, `Website Development`, or specific sprint/retainer) |
| `engagement_mode` | `string(40)` | Default `'sprint_fixed'` | `sprint_fixed`, `monthly_retainer`, or `architecture_review` |
| `project_type` | `string(80)` | Nullable | e.g., `Existing App Needs Deployment/CI-CD`, `Security & DevSecOps Audit`, `New SaaS / Web App Build`, `Website / E-Commerce Redesign` |
| `budget_range` | `string(80)` | Nullable | e.g., `$1,200 – $2,500`, `$2,500 – $5,000`, `$5,000+`, `Monthly Retainer ($750 – $3,200/mo)` |
| `timeline` | `string(80)` | Nullable | e.g., `Immediate (Within 1–2 Weeks)`, `This Month`, `Planning / Next Quarter` |
| `message` | `text` | Required | Repository context, current stack, or deployment bottleneck description |
| `status` | `string(30)` | Default `'new'`, Indexed | Workflow state: `new`, `in_review`, `responded`, `archived` |
| `admin_notes` | `text` | Nullable | Private engineering scoping notes managed in `/admin` |
| `ip_address` | `string(45)` | Nullable | Request IP (`$request->ip()`) |
| `user_agent` | `string(500)` | Nullable | Truncated user-agent header |
| `responded_at` | `timestamp` | Nullable | Timestamp when status transitions to `responded` |
| `created_at` / `updated_at` | `timestamps` | Nullable | Audit timestamps |

### 3.2 `App\Models\Inquiry` Constants & Scopes
- **Status Constants**:
  - `STATUS_NEW = 'new'`
  - `STATUS_IN_REVIEW = 'in_review'`
  - `STATUS_RESPONDED = 'responded'`
  - `STATUS_ARCHIVED = 'archived'`
- **Engagement Mode Constants**:
  - `MODE_SPRINT_FIXED = 'sprint_fixed'`
  - `MODE_MONTHLY_RETAINER = 'monthly_retainer'`
  - `MODE_ARCHITECTURE_REVIEW = 'architecture_review'`
- **Scopes**:
  - `scopeUnread(Builder $query)` → `where('status', self::STATUS_NEW)`
  - `scopeActivePipeline(Builder $query)` → `whereIn('status', [self::STATUS_NEW, self::STATUS_IN_REVIEW])`

---

## 4. Security, Validation & Anti-Spam Design

1. **FormRequest Validation (`App\Http\Requests\StoreInquiryRequest`)**:
   - `name`: `['required', 'string', 'min:2', 'max:120']`
   - `email`: `['required', 'email:filter', 'max:190']`
   - `company`: `['nullable', 'string', 'max:150']`
   - `service_interest`: `['required', 'string', 'max:120']`
   - `engagement_mode`: `['nullable', 'string', 'in:sprint_fixed,monthly_retainer,architecture_review']`
   - `project_type`: `['nullable', 'string', 'max:80']`
   - `budget_range`: `['nullable', 'string', 'max:80']`
   - `timeline`: `['nullable', 'string', 'max:80']`
   - `message`: `['required', 'string', 'min:10', 'max:5000']`
   - `website_url` (Honeypot): `['nullable', ' string', 'max:0']` — hidden field via `aria-hidden="true"` and offscreen CSS; if non-empty, validation fails with `422 Unprocessable Entity` so automated spam bots are rejected before touching the database.
2. **Rate Limiting & CSRF (`routes/web.php`)**:
   - Route: `Route::post('/inquiries', [InquiryController::class, 'store'])->middleware('throttle:5,1')->name('inquiries.store');`
   - Enforces max 5 requests per minute per IP address (`429 Too Many Requests` on 6th attempt) and standard Laravel `web` CSRF verification.
3. **Output Encoding**:
   - All fields rendered in Blade or Filament use escaped output (`{{ ... }}`).

---

## 5. Public Frontend UX (`#consultation` Section & Modal)

1. **On-Page `#consultation` Architecture Scoping Section (`resources/views/welcome.blade.php`)**:
   - Placed between `#faq` and the footer so `#consultation` anchor links scroll smoothly to a comprehensive engineering intake form alongside direct studio telemetry (`engineering@c28studios.com`, typical response SLA `< 1 business day`, and 3-step scoping workflow).
   - Displays a green confirmation banner when `session('inquiry_submitted')` is present and inline field validation errors if submitted without JS.
2. **Interactive `#consultationModal` (`resources/views/layouts/app.blade.php` & `resources/js/app.js`)**:
   - Clicking any Service card, Pricing Tier CTA, or `"Book a Consultation"` button opens the modal and pre-populates `service_interest` and `engagement_mode`.
   - Submits asynchronously via `fetch('/inquiries', { method: 'POST', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': ... } })` and displays either field-level validation feedback or the verified confirmation state.

---

## 6. Filament v3 `/admin` Lead Pipeline (`InquiryResource`)

- **Navigation Group**: `Lead & Client Pipeline` (`$navigationSort = 1`)
- **Navigation Badge**: `Inquiry::query()->unread()->count()` with `success` (emerald) badge color when `> 0`.
- **Table Columns**:
  - `status` badge (`new` → `success`, `in_review` → `warning`, `responded` → `info`, `archived` → `gray`)
  - `name` & `company` (searchable)
  - `email` (copyable, searchable)
  - `service_interest` & `engagement_mode` badge
  - `budget_range` & `timeline`
  - `created_at` (`since()`)
- **Quick Table Actions**:
  - `Mark Responded` action updating `status = 'responded'` and `responded_at = now()`
  - `View / Edit` action opening the full inquiry dossier and `admin_notes` editor.

---

## 7. File-by-File Implementation Plan

| Action | File Path | Description |
| :--- | :--- | :--- |
| **CREATE** | `database/migrations/2026_11_13_000007_create_inquiries_table.php` | Creates `inquiries` table with status, scope, and telemetry columns |
| **CREATE** | `app/Models/Inquiry.php` | Eloquent model with status/mode constants, casts, and scopes |
| **CREATE** | `database/factories/InquiryFactory.php` | Factory for automated testing |
| **CREATE** | `app/Http/Requests/StoreInquiryRequest.php` | FormRequest validation + honeypot `website_url` rule |
| **CREATE** | `app/Http/Controllers/Public/InquiryController.php` | Handles `POST /inquiries` (JSON + redirect responses) |
| **MODIFY** | `routes/web.php` | Registers `POST /inquiries` with `throttle:5,1` |
| **MODIFY** | `resources/views/welcome.blade.php` | Adds the `#consultation` Architecture Scoping form section |
| **MODIFY** | `resources/views/layouts/app.blade.php` | Wires `#consultationModal` form fields + honeypot to `POST /inquiries` |
| **MODIFY** | `resources/js/app.js` | Updates `c28SubmitModal` to submit via `fetch('/inquiries')` with CSRF |
| **CREATE** | `app/Filament/Resources/InquiryResource.php` (+ `ListInquiries`, `CreateInquiry`, `EditInquiry`) | Filament v3 Lead & Client Pipeline resource |
| **MODIFY** | `app/Filament/Widgets/C28StatsOverview.php` | Adds live Inquiry Pipeline stat card |
| **CREATE** | `tests/Feature/LeadCaptureInquiryTest.php` | 6 feature tests covering submission, validation, honeypot, throttling, and Filament resource |

---

## 8. Verification Plan (Stage C)

1. `php artisan test --ansi` — All existing suites (`FoundationTest`, `DomainSchemaTest`, `AdminCmsResourcesTest`, `PublicFrontendTest`) plus `LeadCaptureInquiryTest` must pass 100%.
2. `npm run build` — Verify clean Vite asset compilation.
3. `php artisan route:list --ansi` — Verify `POST inquiries` (`inquiries.store`) and `/admin/inquiries` routes are registered.
4. `php artisan view:cache && php artisan view:clear` — Verify all Blade templates compile cleanly.

---

## 9. Risk Register & Rollback Plan

| Risk | Likelihood | Impact | Mitigation |
| :--- | :--- | :--- | :--- |
| Automated bot spam on public form | Medium | Medium | Combine honeypot (`website_url` max:0) with `throttle:5,1` IP rate limiting and strict server-side validation |
| Modal AJAX submission vs. standard form POST mismatch | Low | Medium | `InquiryController@store` checks `$request->expectsJson()` to return `201 JSON` for modal `fetch` and `302 Redirect` with flash message for standard HTML form POST |

**Rollback Plan**: Revert `2026_11_13_000007_create_inquiries_table.php` via `php artisan migrate:rollback --step=1` and remove the Phase 05 controller/resource files.

---

## 10. Approval Gate (Stage A Hard Stop)

This design review is complete and awaits explicit user approval before any Phase 05 code is implemented.

