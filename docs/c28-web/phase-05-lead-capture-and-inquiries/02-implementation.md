# Phase 05 — Implementation Summary: Lead Capture, Consultation Inquiries & Admin Pipeline Management

**Project**: C28 — Software & Cloud Engineering (`c28studios`)
**Phase**: `phase-05-lead-capture-and-inquiries`
**Stage**: Stage D — Implementation Documentation
**Date**: 2026-11-13
**Status**: Implemented & Verified

---

## 1. Executive Summary

Phase 05 implemented the end-to-end engineering consultation and lead capture pipeline for **C28 — Software & Cloud Engineering**, connecting the public-facing `#consultation` Architecture Scoping section and `#consultationModal` drawer to a CSRF-protected, rate-limited (`throttle:5,1`), honeypot-guarded (`website_url`) backend endpoint (`POST /inquiries`) and a dedicated Filament v3 CRM pipeline resource (`InquiryResource`) inside `/admin`.

---

## 2. Files Created & Modified

### 2.1 Database Schema, Eloquent Model & Factory
- [2026_11_13_000007_create_inquiries_table.php](file:///C:/dev/c28studios/database/migrations/2026_11_13_000007_create_inquiries_table.php) — Creates the `inquiries` table storing `name`, `email` (indexed), `company`, `service_interest` (indexed), `engagement_mode` (default `'sprint_fixed'`), `project_type`, `budget_range`, `timeline`, `message`, `status` (default `'new'`, indexed), `admin_notes`, `ip_address`, `user_agent`, and `responded_at`.
- [Inquiry.php](file:///C:/dev/c28studios/app/Models/Inquiry.php) — Eloquent model defining status constants (`STATUS_NEW`, `STATUS_IN_REVIEW`, `STATUS_RESPONDED`, `STATUS_ARCHIVED`), engagement mode constants (`MODE_SPRINT_FIXED`, `MODE_MONTHLY_RETAINER`, `MODE_ARCHITECTURE_REVIEW`), `casts()`, `scopeUnread()`, `scopeActivePipeline()`, `statusLabel()`, and `engagementModeLabel()`.
- [InquiryFactory.php](file:///C:/dev/c28studios/database/factories/InquiryFactory.php) — Model factory generating realistic C28 consultation inquiries for automated feature testing.

### 2.2 Security, Anti-Spam Validation & Controller Endpoint
- [StoreInquiryRequest.php](file:///C:/dev/c28studios/app/Http/Requests/StoreInquiryRequest.php) — Dedicated `FormRequest` enforcing strict validation rules on `name`, `email`, `company`, `service_interest`, `engagement_mode`, `project_type`, `budget_range`, `timeline`, and `message` (`min:10`), plus the anti-spam honeypot rule (`'website_url' => ['nullable', 'string', 'max:0']`).
- [InquiryController.php](file:///C:/dev/c28studios/app/Http/Controllers/Public/InquiryController.php) — Persists validated inquiries with `status = 'new'`, client IP, and truncated user-agent, returning `201 Created` JSON for asynchronous modal submissions (`$request->expectsJson()`) or a `302 Redirect` to `/#consultation` with `session('inquiry_submitted')` for standard HTML form submissions.
- [routes/web.php](file:///C:/dev/c28studios/routes/web.php) — Registers `POST /inquiries` (`name('inquiries.store')`) protected by `throttle:5,1` and `web` CSRF middleware.

### 2.3 Public Frontend Integration (`#consultation` & `#consultationModal`)
- [welcome.blade.php](file:///C:/dev/c28studios/resources/views/welcome.blade.php) — Added Section 11 (`#consultation`: Architecture Scoping & Consultation Intake) featuring the 3-step C28 scoping workflow, `session('inquiry_submitted')` confirmation alert, inline validation error summary, hidden `website_url` honeypot field, and comprehensive engineering intake fields.
- [app.blade.php](file:///C:/dev/c28studios/resources/views/layouts/app.blade.php) — Upgraded `#consultationModal` with named form inputs (`name`, `email`, `company`, `engagement_mode`, `service_interest`, `message`), hidden honeypot field (`website_url` with `aria-hidden="true"`), `#modalErrors` container, and CSRF token.
- [resources/js/app.js](file:///C:/dev/c28studios/resources/js/app.js) — Updated `c28OpenModal` to pre-select the matching C28 service tier and engagement mode, and updated `c28SubmitModal` to submit via `fetch('/inquiries')` with `X-CSRF-TOKEN` and dynamic error/success handling.

### 2.4 Filament v3 `/admin` Lead Pipeline
- [InquiryResource.php](file:///C:/dev/c28studios/app/Filament/Resources/InquiryResource.php) (+ [ListInquiries.php](file:///C:/dev/c28studios/app/Filament/Resources/InquiryResource/Pages/ListInquiries.php), [CreateInquiry.php](file:///C:/dev/c28studios/app/Filament/Resources/InquiryResource/Pages/CreateInquiry.php), [EditInquiry.php](file:///C:/dev/c28studios/app/Filament/Resources/InquiryResource/Pages/EditInquiry.php)) — Placed in the `Lead & Client Pipeline` navigation group with a live `success` navigation badge showing unread (`status = 'new'`) inquiry count, status/mode filters, searchable prospect columns, a one-click `Mark Responded` table action, and an edit view where prospect fields are read-only (`disabledOn('edit')`) while `status`, `responded_at`, and `admin_notes` are editable.
- [C28StatsOverview.php](file:///C:/dev/c28studios/app/Filament/Widgets/C28StatsOverview.php) — Added the 5th live telemetry card (`Unread Inquiries` / `Active in Engineering Pipeline`).

### 2.5 Automated Feature Test Suite
- [LeadCaptureInquiryTest.php](file:///C:/dev/c28studios/tests/Feature/LeadCaptureInquiryTest.php) — 5 feature tests verifying HTML (`302`) and JSON (`201`) inquiry submissions, honeypot spam rejection (`422`), field validation errors (`422`), rate limiting (`throttle:5,1` returning `429` on the 6th request), and Filament `InquiryResource` authorization (`403` for non-admin, `200` + `markResponded` + `EditInquiry` triage for admin).

