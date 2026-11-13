# Phase 05 — Acceptance Report: Lead Capture, Consultation Inquiries & Admin Pipeline Management

**Project**: C28 — Software & Cloud Engineering (`c28studios`)
**Phase**: `phase-05-lead-capture-and-inquiries`
**Stage**: Stage D — Verification & Acceptance
**Date**: 2026-11-13
**Status**: **VERIFIED & ACCEPTED**

---

## 1. Acceptance Criteria Checklist

| # | Criterion | Verification Method | Status |
| :--- | :--- | :--- | :--- |
| 1 | `inquiries` migration (`2026_11_13_000007_create_inquiries_table.php`), `App\Models\Inquiry` (with status & engagement mode constants, `scopeUnread()`, `scopeActivePipeline()`), and `InquiryFactory` are created and migrated | `php artisan migrate --ansi` & `LeadCaptureInquiryTest` | **PASS** |
| 2 | `POST /inquiries` (`InquiryController@store`) validates via `StoreInquiryRequest`, returns `201 Created` for JSON/AJAX modal requests and `302 Redirect` with `session('inquiry_submitted')` for HTML form requests | `LeadCaptureInquiryTest::test_valid_html_and_json_inquiry_submissions_persist_and_return_expected_responses` | **PASS** |
| 3 | Anti-spam honeypot field (`website_url`, `max:0`) rejects automated bot payloads with `422 Unprocessable Entity` without persisting to the database | `LeadCaptureInquiryTest::test_honeypot_field_rejects_automated_spam_submissions` | **PASS** |
| 4 | Rate limiting (`throttle:5,1`) blocks excessive requests with `429 Too Many Requests` on the 6th submission within 1 minute | `LeadCaptureInquiryTest::test_rate_limiting_blocks_excessive_inquiry_submissions_with_429` | **PASS** |
| 5 | Public `#consultation` section (`welcome.blade.php`) and `#consultationModal` (`layouts/app.blade.php` + `resources/js/app.js`) include CSRF, honeypot, and dynamic error/success feedback | `npm run build` & `LeadCaptureInquiryTest` | **PASS** |
| 6 | Filament `InquiryResource` (`Lead & Client Pipeline` group) enforces `403` for non-admins, displays unread badge count, supports one-click `Mark Responded`, and allows editing `status` and `admin_notes` | `LeadCaptureInquiryTest::test_filament_inquiry_resource_enforces_admin_auth_and_supports_triage_workflow` | **PASS** |
| 7 | `C28StatsOverview` widget includes the 5th telemetry card (`Unread Inquiries` / `Active in Engineering Pipeline`) | `AdminCmsResourcesTest` & `LeadCaptureInquiryTest` | **PASS** |

---

## 2. Observed Verification Results (Stage C)

### 2.1 Automated PHPUnit Test Suite (`php artisan test --ansi`)
- **Command**: `php artisan test --ansi`
- **Exit Code**: `0`
- **Observed Output**:
  - `Tests\Unit\ExampleTest`: 1 passed
  - `Tests\Feature\AdminCmsResourcesTest`: 7 passed
  - `Tests\Feature\DomainSchemaTest`: 6 passed
  - `Tests\Feature\ExampleTest`: 1 passed
  - `Tests\Feature\FoundationTest`: 5 passed
  - `Tests\Feature\LeadCaptureInquiryTest`: 5 passed
  - `Tests\Feature\PublicFrontendTest`: 6 passed
  - **Total**: **31 passed (195 assertions)** in `60.01s`.

### 2.2 Production Asset Build (`npm run build`)
- **Command**: `npm run build`
- **Exit Code**: `0`
- **Observed Output**:
  - `✓ 62 modules transformed.`
  - `public/build/manifest.json` (`0.27 kB | gzip: 0.15 kB`)
  - `public/build/assets/app-D0RtTUSX.css` (`27.12 kB | gzip: 5.66 kB`)
  - `public/build/assets/app-Ck96gQkc.js` (`122.47 kB | gzip: 43.82 kB`)
  - `✓ built in 3.27s`

### 2.3 Route Verification (`php artisan route:list --ansi`)
- **Observed New Routes**:
  - `POST inquiries` → `inquiries.store › Public\InquiryController@store`
  - `GET|HEAD admin/inquiries` → `filament.admin.resources.inquiries.index › App\Filament\Resources\InquiryResource\Pages\ListInquiries`
  - `GET|HEAD admin/inquiries/create` → `filament.admin.resources.inquiries.create › App\Filament\Resources\InquiryResource\Pages\CreateInquiry`
  - `GET|HEAD admin/inquiries/{record}/edit` → `filament.admin.resources.inquiries.edit › App\Filament\Resources\InquiryResource\Pages\EditInquiry`
  - Total registered application routes: **39 routes**.

---

## 3. Sign-Off

Phase 05 (`phase-05-lead-capture-and-inquiries`) is **Verified & Accepted**.

