# Phase 09 — Comprehensive Security Hardening, Anti-Tracking & Technical/AI SEO (`01-design-review.md`)

## 1. Objective & Scope

Harden **C28 — Software & Cloud Engineering** (`c28studios`) against XSS/JS injection, SQL injection, malicious file uploads, exploit scanners/scrapers, and third-party browser tracking while maximizing search engine rankings (Google, Bing, DuckDuckGo) and AI engine discoverability (ChatGPT / OpenAI Search, Perplexity, Claude, Google AI Overviews).

---

## 2. Security & Anti-Tracking Architecture

### 2.1 HTTP Security, CSP & Anti-Tracking Headers (`App\Http\Middleware\SecurityHeadersMiddleware`)
Applied globally to the `web` middleware group:
- **Anti-Tracking & Privacy (`Permissions-Policy` & `Referrer-Policy`)**:
  - `Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=(), usb=(), interest-cohort=(), browsing-topics=(), join-ad-interest-group=(), run-ad-auction=()` — disables browser FLoC/Topics ad-tracking cohorts and hardware sensors.
  - `Referrer-Policy: strict-origin-when-cross-origin` — prevents leaking full internal URLs to external sites.
  - `X-DNS-Prefetch-Control: off` — prevents passive DNS prefetch tracking.
- **Injection & Clickjacking Defense**:
  - `X-Content-Type-Options: nosniff` — prevents MIME-sniffing attacks on uploaded files or responses.
  - `X-Frame-Options: SAMEORIGIN` — blocks external iframe clickjacking.
  - `Cross-Origin-Opener-Policy: same-origin` — isolates browsing context window references.
  - `Content-Security-Policy` (Public routes): Restricts `default-src 'self'`, `object-src 'none'`, `base-uri 'self'`, `frame-ancestors 'self'`, `form-action 'self'`, `img-src 'self' data: https:`, `font-src 'self' https://fonts.gstatic.com data:`, `style-src 'self' 'unsafe-inline' https://fonts.googleapis.com`, `script-src 'self' 'unsafe-inline'`.
  - `Strict-Transport-Security: max-age=63072000; includeSubDomains; preload` (applied when request is HTTPS).
  - Removes server fingerprinting headers (`X-Powered-By`).

### 2.2 Malicious Scraper & Vulnerability Scanner Blocking
- Inside `SecurityHeadersMiddleware`, inspect `User-Agent` and block known exploit scanners, SQLi tools, and aggressive vulnerability harvesters (`sqlmap`, `nikto`, `acunetix`, `nessus`, `nuclei`, `masscan`, `zgrab`, `dirbuster`, `gobuster`, `wfuzz`, `havij`, `libwww-perl`) with `403 Forbidden`.
- Explicitly allow all legitimate search engines (`Googlebot`, `Bingbot`, `DuckDuckBot`, `Applebot`, `Slurp`, `YandexBot`) and AI discovery agents (`OAI-SearchBot`, `ChatGPT-User`, `GPTBot`, `ClaudeBot`, `PerplexityBot`, `Google-Extended`).

### 2.3 Input Sanitization, XSS & SQL Injection Hardening
1. **Form Request Sanitization (`App\Http\Requests\StoreInquiryRequest`)**:
   - Add `prepareForValidation()` to strip HTML/script tags (`strip_tags`), remove null bytes/control characters, and normalize whitespace across all public form fields (`name`, `email`, `company`, `service_interest`, `project_type`, `budget_range`, `timeline`, `message`).
   - Add validation rule rejecting strings containing executable injection vectors (`<script`, `javascript:`, `onerror=`, `onload=`, `data:text/html`).
2. **DOM XSS Elimination (`resources/js/app.js`)**:
   - Replace `errorsBox.innerHTML` in `window.c28SubmitModal` with safe DOM element creation (`document.createElement('div')` + `textContent`) so server or network error strings can never execute HTML/JS in the browser.
3. **URL Scheme Sanitization in Blade Views**:
   - Ensure dynamic external URLs (`repo_url`, `live_url`, `button_url`, `github_url`, `contact.website`) only allow `http://`, `https://`, or relative `/` paths (blocking `javascript:` or `data:` URI injection).
4. **Parameterized Query & Route Constraint Audit**:
   - Add strict slug regex constraints (`->where('project', '[a-z0-9\-]+')` and `->where('post', '[a-z0-9\-]+')`) in `routes/web.php` so malformed/SQLi-style URLs immediately return `404` before hitting the database.

### 2.4 Admin Panel (`/admin`) & File Upload Security (`SiteSettingResource` & `AdminPanelProvider`)
- **Upload MIME & Extension Lockdown (`SiteSettingResource`)**:
  - Restrict `FileUpload::make('value')` to safe raster MIME types only: `acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])` (explicitly blocking `.svg` / `image/svg+xml` which can carry embedded `<script>` XSS payloads, as well as `.php`, `.html`, `.js`, `.exe`).
  - Enforce `maxSize(2048)` (2 MB) and `regex:/^[a-zA-Z0-9._\-]+$/` on `SiteSetting` keys.
- **Storage Execution Prevention**:
  - Add `public/storage/.htaccess` / `storage/app/public/.htaccess` rules disabling script execution inside uploaded asset directories.

---

## 3. Technical SEO & AI Engine Optimization (Google, Bing, ChatGPT, Perplexity, Claude)

### 3.1 Meta Tags, Canonical URLs, OpenGraph & Twitter Cards (`layouts/app.blade.php`, `welcome.blade.php`, `projects/show.blade.php`, `posts/show.blade.php`)
- Every public page renders:
  - Dynamic `<title>` and `<meta name="description">` tailored to the page (Homepage, Project Dossier, or Engineering Walkthrough).
  - `<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">`.
  - Canonical link (`<link rel="canonical" href="...">`).
  - Complete OpenGraph tags (`og:type`, `og:site_name`, `og:title`, `og:description`, `og:url`, `og:image`, `og:locale`) and Twitter Card tags (`twitter:card`, `twitter:title`, `twitter:description`, `twitter:image`).

### 3.2 Schema.org JSON-LD Structured Data (`application/ld+json`)
- **Homepage (`welcome.blade.php`)**:
  - `ProfessionalService` / `Organization` schema with official business name (`C28 — Software & Cloud Engineering`), URL (`https://www.c28studios.dev`), email (`contact@c28studios.dev`), telephone (`+234-803-3880-229` / `803 3880 229`), and `hasOfferCatalog` listing the 4-Tier Service Hierarchy (`DevOps & Cloud Engineering`, `DevSecOps Integration`, `Software Development`, `Website Development`).
  - `WebSite` schema.
  - `FAQPage` schema dynamically generated from active `$faqs` so Google rich results and AI engines extract direct Q&A answers.
- **Project Dossiers (`projects/show.blade.php`)**:
  - `TechArticle` + `BreadcrumbList` JSON-LD schema.
- **Engineering Walkthroughs (`posts/show.blade.php`)**:
  - `TechArticle` / `BlogPosting` + `BreadcrumbList` JSON-LD schema.

### 3.3 Dynamic XML Sitemap (`/sitemap.xml`) & Crawler Directives (`/robots.txt`)
- **`GET /sitemap.xml` (`App\Http\Controllers\Public\SeoController@sitemap`)**:
  - Generates valid XML (`application/xml`) containing the homepage (`/`), all published project pages (`/projects/{slug}`), and all published walkthroughs (`/insights/{slug}`) with `<lastmod>`, `<changefreq>`, and `<priority>`.
- **`public/robots.txt` + `GET /robots.txt`**:
  - Explicitly allows `Googlebot`, `Bingbot`, `DuckDuckBot`, `OAI-SearchBot`, `ChatGPT-User`, `GPTBot`, `ClaudeBot`, `PerplexityBot`, and `Google-Extended` to crawl public pages (`Allow: /`), while disallowing `/admin`, `/livewire`, and `/inquiries`, and referencing `Sitemap: https://www.c28studios.dev/sitemap.xml` and `/llms.txt`.

### 3.4 AI Discovery Endpoints (`/llms.txt` & `/llms-full.txt`)
- **`GET /llms.txt` & `GET /llms-full.txt` (`App\Http\Controllers\Public\SeoController@llms`)**:
  - Implements the `llms.txt` standard in clean Markdown so ChatGPT, Claude, Perplexity, and AI search crawlers can immediately index C28's identity, 4-tier service hierarchy, transparent sprint/retainer pricing, contact details (`803 3880 229`, `contact@c28studios.dev`, `www.c28studios.dev`), and direct links + summaries of all published Case Studies, Lab Demonstrations, and Engineering Walkthroughs.
  - Linked in `<head>` via `<link rel="alternate" type="text/markdown" href="/llms.txt" title="LLM Context & AI Summary">`.

---

## 4. Verification Plan
1. Add feature tests in `tests/Feature/SecurityAndSeoTest.php` verifying:
   - Security & anti-tracking headers (`Content-Security-Policy`, `Permissions-Policy`, `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`) on public responses.
   - XSS/HTML stripping and injection rejection on `POST /inquiries`.
   - Malicious scanner User-Agent (`sqlmap`, `nikto`) receives `403 Forbidden`, while `Googlebot` and `ChatGPT-User` / `OAI-SearchBot` receive `200 OK`.
   - `GET /sitemap.xml` returns `200 OK` (`application/xml`) with published projects and posts (and excludes unpublished drafts).
   - `GET /llms.txt` returns `200 OK` (`text/plain`) with C28's 4-tier services, contact details, and published URLs.
   - Homepage, project, and post pages render canonical links, meta descriptions, OpenGraph tags, and valid `application/ld+json` structured data.
2. Run `npm run build` and `php artisan test`.
