# Phase 08 — Design Review: Back-to-Top Button, Dark/Light Theme System, Admin Top/Bottom Logos & Dynamic Site Name

**Project**: C28 — Software & Cloud Engineering (`c28studios`)
**Phase**: `phase-08-theme-branding-and-ux`
**Stage**: Stage A — Design Review (Approval Gate)
**Date**: 2026-11-15
**Status**: **AWAITING USER APPROVAL (HARD STOP)**

---

## 1. Objective & User-Visible Outcome

Implement four user-requested enhancements across the public storefront and Filament `/admin` panel:

1. **Bottom-to-Top ("Back to Top") Button**:
   - A clean, architectural floating button (`fixed bottom-6 right-6 z-40`) that stays hidden at the top of the page and fades in once the user scrolls down (`scrollY > 320px`). Clicking it smoothly scrolls back to the top of the page.
2. **Dark Theme (`#181818`) / White Theme (`#FFFFFF` / `#F8FAFC`) Support**:
   - Admin setting (`theme.mode` in `/admin/site-settings` under `Brand Identity` / `General`, supporting `dark` or `light` default) **plus** an interactive Dark/Light theme toggle button in the top navigation bar (saved in `localStorage` with fallback to the admin default).
   - Full Light/White theme styling in `resources/css/app.css` (`html.theme-light`) so all backgrounds, cards, text hierarchy, and architectural grid lines remain crisp and visible in both Dark (`#181818`) and White (`#FFFFFF`) themes.
3. **Admin Top & Bottom Logo Upload (`brand.logo_top`, `brand.logo_bottom`, `brand.logo_text`)**:
   - Extend `SiteSetting` and `SiteSettingResource` in Filament (`/admin/site-settings`) with `SiteSetting::TYPE_IMAGE` (`FileUpload` stored on the `public` disk under `branding/`) so the admin can upload/update:
     - **Top Header Logo (`brand.logo_top`)**
     - **Bottom Footer Logo (`brand.logo_bottom`)**
     - **Fallback Logo Badge Text (`brand.logo_text`, default `C28`)** when no image is uploaded.
4. **Dynamic Site Name Everywhere (`brand.name`)**:
   - Replace the remaining hardcoded `"C28 Studios"` in the top header (`layouts/app.blade.php` L33) and `"C28Studios"` in the footer watermark (`layouts/app.blade.php` L137) with `$brandName` (`$settings['brand.name']`), so changing `brand.name` in `/admin/site-settings` updates the header, page title, footer title, copyright line, and watermark simultaneously.

---

## 2. Existing-State Findings

| File | Current State | Planned Change |
| :--- | :--- | :--- |
| `resources/views/layouts/app.blade.php` | Header brand text on L33 is hardcoded as `C28 Studios`; top/bottom logo badges on L30 & L86 are hardcoded `C28` divs; no Back-to-Top button or theme switcher. | Bind header/footer/watermark to `$brandName`; render uploaded `brand.logo_top` / `brand.logo_bottom` `<img>` when set (falling back to `brand.logo_text`); add theme toggle button in header and `#c28BackToTop` button before `</body>`. |
| `app/Models/SiteSetting.php` | Supports `TYPE_TEXT`, `TYPE_JSON`, `TYPE_BOOLEAN`. | Add `TYPE_IMAGE = 'image'` constant (stored as relative path string in `value`). |
| `app/Filament/Resources/SiteSettingResource.php` | Renders `Textarea` for text/boolean and `Textarea` for JSON. | Add `FileUpload::make('value')` when `type === SiteSetting::TYPE_IMAGE` (disk `public`, directory `branding`, image validation). |
| `app/Http/Controllers/Public/HomeController.php` & `database/seeders/C28ContentSeeder.php` | Seeds and resolves `brand.name`, `brand.statement`, etc. | Add defaults and seeded rows for `brand.logo_top`, `brand.logo_bottom`, `brand.logo_text`, and `theme.mode` (`dark`). |
| `resources/css/app.css` & `resources/js/app.js` | Hardcoded `#181818` dark theme only; no scroll-to-top handler. | Add `html.theme-light` CSS rules preserving visible grids/borders in white mode, plus `c28ToggleTheme()`, early theme initialization (preventing flash), and `c28ScrollToTop()` scroll listener in `app.js`. |

---

## 3. Security, Validation & Compatibility

- **File Uploads**: Restricted to admin users via `SiteSettingResource` (`canAccessPanel` enforces `is_admin = true`), validated as images (`image()`, `maxSize(2048)`).
- **Output Encoding**: All `$brandName`, `$logoText`, and asset URLs are escaped with `{{ ... }}`.
- **Backwards Compatibility**: If `brand.logo_top` or `brand.logo_bottom` is empty, the existing `C28` square badge renders automatically so existing tests and layouts never break.

---

## 4. Expected Files to Modify

1. `app/Models/SiteSetting.php`
2. `app/Filament/Resources/SiteSettingResource.php`
3. `app/Http/Controllers/Public/HomeController.php`
4. `database/seeders/C28ContentSeeder.php`
5. `resources/views/layouts/app.blade.php`
6. `resources/css/app.css`
7. `resources/js/app.js`
8. `tests/Feature/PublicFrontendTest.php` & `tests/Feature/AdminCmsResourcesTest.php`

---

> **Stage A complete — design review only. No implementation files were changed. Awaiting explicit approval to begin implementation.**
