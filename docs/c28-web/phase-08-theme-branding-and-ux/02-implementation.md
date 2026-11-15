# Phase 08 — Implementation Record: Back-to-Top Button, Dark/Light Theme System, Admin Top/Bottom Logos & Dynamic Site Name

**Project**: C28 — Software & Cloud Engineering (`c28studios`)
**Phase**: `phase-08-theme-branding-and-ux`
**Stage**: Stage B / D — Implementation Record
**Date**: 2026-11-15

---

## 1. Summary of Changes

1. **Bottom-to-Top ("Back to Top") Button (`resources/views/layouts/app.blade.php`, `resources/js/app.js`)**:
   - Added `#c28BackToTop` floating button (`fixed bottom-6 right-6 z-40`) that appears when `window.scrollY > 320` and smoothly scrolls to the top via `window.c28ScrollToTop()`.
2. **Dark (`#181818`) / White (`#FFFFFF`) Theme System (`resources/css/app.css`, `resources/js/app.js`, `resources/views/layouts/app.blade.php`)**:
   - Added `theme.mode` (`dark` or `light`) setting in `SiteSetting` (`HomeController::resolveSettings()` and `C28ContentSeeder`).
   - Added `#c28ThemeToggle` button in the top navigation bar (`window.c28ToggleTheme()`), persisting user preference in `localStorage.getItem('c28_theme')` with early `<head>` initialization to prevent theme flash.
   - Added `html.theme-light` rules in `resources/css/app.css` so backgrounds, cards, typography, and architectural grid lines/borders remain crisp and visible in both Dark (`#181818`) and White (`#FFFFFF` / `#F8FAFC`) modes.
3. **Admin Top & Bottom Logo Upload (`app/Models/SiteSetting.php`, `app/Filament/Resources/SiteSettingResource.php`)**:
   - Added `SiteSetting::TYPE_IMAGE = 'image'` and a Filament `FileUpload` field (`disk('public')->directory('branding')`) in `SiteSettingResource`.
   - Seeded `brand.logo_top`, `brand.logo_bottom`, and `brand.logo_text` (`C28`) and linked `public/storage` via `php artisan storage:link`.
   - Updated `layouts/app.blade.php` to display the uploaded top logo in the header and bottom logo in the footer (falling back to `brand.logo_text` when empty).
4. **Dynamic Site Name Everywhere (`resources/views/layouts/app.blade.php`)**:
   - Bound `<meta name="author">`, `<title>`, top header brand title, footer brand title, copyright notice, and giant footer watermark to `$brandName` (`brand.name`).
