# Phase 09 — Security Hardening, Anti-Tracking & Technical/AI SEO (`02-implementation.md`)

## 1. Summary of Changes

### 1.1 HTTP Security, CSP & Anti-Tracking Middleware
- Created `app/Http/Middleware/SecurityHeadersMiddleware.php` and registered it on the `web` middleware group in `bootstrap/app.php`.
- **Anti-Tracking & Privacy**:
  - `Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=(), usb=(), interest-cohort=(), browsing-topics=(), join-ad-interest-group=(), run-ad-auction=()`
  - `Referrer-Policy: strict-origin-when-cross-origin`
  - `X-DNS-Prefetch-Control: off`
- **Anti-Injection & Clickjacking**:
  - `Content-Security-Policy` on public routes (`default-src 'self'`, `object-src 'none'`, `base-uri 'self'`, `frame-ancestors 'self'`, `form-action 'self'`, plus safe image/font/style policies).
  - `X-Content-Type-Options: nosniff`
  - `X-Frame-Options: SAMEORIGIN`
  - `Cross-Origin-Opener-Policy: same-origin`
  - `Strict-Transport-Security` on HTTPS requests and removal of `X-Powered-By`.
- **Malicious Bot & Scanner Blocking**:
  - Rejects automated vulnerability scanners and SQLi tools (`sqlmap`, `nikto`, `acunetix`, `nessus`, `nuclei`, `masscan`, `zgrab`, `dirbuster`, `gobuster`, `wfuzz`, `havij`) with `403 Forbidden`, while allowing `Googlebot`, `Bingbot`, `DuckDuckBot`, `OAI-SearchBot`, `ChatGPT-User`, `GPTBot`, `ClaudeBot`, `PerplexityBot`, and `Google-Extended`.

### 1.2 Input Sanitization, XSS & Upload Lockdown
- Updated `app/Http/Requests/StoreInquiryRequest.php`:
  - Inspects raw inputs for `<script>`, `javascript:`, `vbscript:`, `data:text/html`, `<iframe`, `<object`, `<embed`, `<svg`, and `on*=` event handlers and rejects them with `422 Unprocessable Entity`.
  - Strips control characters and HTML tags (`strip_tags`) in `prepareForValidation()`.
- Updated `resources/js/app.js`:
  - Replaced `errorsBox.innerHTML` in `window.c28SubmitModal` with safe DOM creation (`document.createElement('div')` + `textContent`).
- Updated `app/Filament/Resources/SiteSettingResource.php` & `storage/app/public/.htaccess`:
  - Restricted image uploads to raster formats only (`image/png`, `image/jpeg`, `image/webp` up to 2 MB), constrained `key` format via regex, and disabled script execution in public storage.
- Added strict route slug regex constraints (`[a-z0-9\-]+`) in `routes/web.php` and URL scheme validation across Blade templates (`layouts/app.blade.php`, `projects/show.blade.php`, `components/blocks/cta_banner.blade.php`).

### 1.3 Technical SEO & AI Discovery (`SeoController`, Meta Tags, JSON-LD)
- Created `app/Http/Controllers/Public/SeoController.php` and registered `/sitemap.xml`, `/robots.txt`, `/llms.txt`, and `/llms-full.txt` in `routes/web.php` (plus updated `public/robots.txt`).
- Added comprehensive `<meta name="description">`, `<meta name="keywords">`, `<meta name="robots">`, `<link rel="canonical">`, `<link rel="alternate" type="text/markdown" href="/llms.txt">`, OpenGraph (`og:*`), and Twitter Card (`twitter:*`) metadata in `resources/views/layouts/app.blade.php`.
- Added Schema.org JSON-LD (`application/ld+json`) structured data:
  - Homepage (`resources/views/welcome.blade.php`): `ProfessionalService` / `Organization`, `WebSite`, and `FAQPage` schemas.
  - Project Dossiers (`resources/views/projects/show.blade.php`): `TechArticle` and `BreadcrumbList` schemas.
  - Engineering Walkthroughs (`resources/views/posts/show.blade.php`): `TechArticle` and `BreadcrumbList` schemas.
