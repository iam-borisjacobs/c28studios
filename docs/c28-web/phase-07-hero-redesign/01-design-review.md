# Phase 07 — Design Review: Architectural Hero Redesign & EZGIF Animation Integration

**Project**: C28 — Software & Cloud Engineering (`c28studios`)
**Phase**: `phase-07-hero-redesign`
**Stage**: Stage A — Design Review (Approval Gate)
**Date**: 2026-11-14
**Status**: **AWAITING USER APPROVAL (HARD STOP)**

---

## 1. Objective & User-Visible Outcome

Redesign the public landing page hero section (and align the top navigation bar) in `c28studios` to match the clean, architectural, hairline-grid layout shown in the user-provided reference image, with the 300-frame EZGIF animation (`c:\Users\User\Downloads\ezgif-2756ed5923b649fb-jpg`) running smoothly on the right-hand side.

### User-Visible Outcome
- **Zero "AI slop" aesthetic**: Removes the blurred vertical light pillars (`.vertical-light-pillar`, `.vertical-light-core`), radial aurora blob (`.hero-beam`), `animate-ping` pill badge, and floating pill buttons from the hero.
- **Crisp Architectural Grid**: A structured 2-column (50/50 on desktop) hairline-bordered layout with subtle `+` crosshair intersection markers on `#050806` obsidian carbon.
- **Left Hero Cell**: Subtle diagonal pinstripe texture, bordered monospace badge (`{{ $heroBadge }}` with `_` cursor), tight high-contrast `Plus Jakarta Sans` headline (`{{ $heroHeadline }}`), concise subheadline (`{{ $heroSubheadline }}`), and two sharp-cornered rectangular CTA buttons (ghost bordered + solid `#00E676` primary).
- **Right Hero Cell**: The 300-frame 3D dotted green dual-cube EZGIF animation (`1280×720`) compiled into a single optimized looping animated WebP (`public/media/hero-cubes.webp`) framed cleanly inside the right cell.
- **Bottom Split Strip**:
  - **Bottom-Left**: Clean monochrome C28 engineering stack strip (`DOCKER`, `GITHUB ACTIONS`, `NGINX TLS 1.3`, `TRIVY`) — strictly adhering to `AGENTS.md` Section 3.2 (Zero Fabrication Policy; no fake client logos).
  - **Bottom-Right**: 3-column separated metric bar (`CORE DISCIPLINES: 04_`, `RELEASE DOWNTIME: 0m_`, `SECURITY GATES: 100%_`).
- **Clean Header Bar**: Single-row architectural header with `C28` brand mark on the left, slash-prefixed monospace navigation (`/ SERVICES`, `/ BLUEPRINT`, `/ PROJECTS`, `/ PRICING`, `/ LAB NOTES`) centered, and a sharp bordered CTA button on the right.

---

## 2. Stage 0 Discovery & Existing-State Findings

| File / Symbol | Current State | Impact |
| :--- | :--- | :--- |
| `resources/views/welcome.blade.php` (L19–207) | Hero uses `.hero-beam`, `.vertical-light-pillar`, `.vertical-light-core`, `animate-ping` pill badge, rounded-full buttons, and embeds the Interactive Control Plane Console directly inside the hero section. | Replace lines 19–70 with the new 2-column architectural grid hero + EZGIF animation + bottom strip; move the Interactive Control Plane Console into its own clean section immediately below so no existing functionality is lost. |
| `resources/views/layouts/app.blade.php` (L25–101) | Includes a top telemetry announcement bar above the sticky header, rounded-full CTA button, and standard nav links. | Streamline header to match the clean single-row hairline-bordered nav with `/ SLASH` monospace links and sharp rectangular CTA button while keeping `#c28MobileNav` intact. |
| `resources/css/app.css` (L14–75) | Defines `.bg-carbon-grid`, `.hero-beam`, `.vertical-light-pillar`, `.vertical-light-core`, `.btn-emerald`. | Add `.bg-hero-hatch` (subtle 45° diagonal pinstripe) and `.hero-crosshair` utility classes without breaking existing section styles used lower on the page. |
| `app/Http/Controllers/Public/HomeController.php` | Resolves `SiteSetting` values (`hero.badge`, `hero.headline`, `hero.subheadline`, `hero.console_tabs`, `cta.primary_label`). | No breaking changes; all CMS `SiteSetting` keys continue to bind dynamically. |
| `tests/Feature/PublicFrontendTest.php` | Verifies `/` returns 200, renders seeded services/projects/pricing/posts/FAQs, and reflects live `SiteSetting::setValue('hero.subheadline', ...)` updates. | All existing assertions remain 100% compatible. Add tests verifying the new hero structure and `hero-cubes.webp` asset reference. |
| `c:\Users\User\Downloads\ezgif-2756ed5923b649fb-jpg` | Contains 300 JPEG frames (`ezgif-frame-001.jpg` – `ezgif-frame-300.jpg`), each `1280×720` RGB (~33–157 KB per frame, ~12 MB total). | Compile into a single optimized looping `public/media/hero-cubes.webp` using Python Pillow `12.3.0` (verified installed). |

---

## 3. Proposed Design & Component Boundaries

```
+-----------------------------------------------------------------------+
| C28          / SERVICES  / BLUEPRINT  / PROJECTS  / PRICING  / LAB    | [Book Consultation]
+-----------------------------------+-----------------------------------+
| [Software & Cloud Engineering _]  |                                   |
|                                   |                                   |
| The Engineering Layer             |    [hero-cubes.webp]              |
| for Production Systems            |    (300-frame looping 3D dotted   |
| (CMS: $heroHeadline)              |     green dual-cube animation)    |
|                                   |                                   |
| Subheadline ($heroSubheadline)    |                                   |
|                                   |                                   |
| [Explore Services] [Book Call]    |                                   |
+-----------------------------------+-----------------------------------+
| DOCKER  GITHUB ACTIONS  NGINX  TRIVY | 04_        | 0m_      | 100%_  |
|                                      | DISCIPLINES| DOWNTIME | GATES  |
+--------------------------------------+------------+----------+--------+
```

### Key Design Decisions
1. **CMS Compatibility**: `{{ $heroBadge }}`, `{{ $heroHeadline }}`, and `{{ $heroSubheadline }}` continue to be read from `$settings` (`HomeController::resolveSettings()`), so Filament `/admin` edits to `SiteSetting` continue to update the hero live.
2. **Preserving the Interactive Control Plane Console**: Rather than deleting the Interactive Control Plane Console (`#consoleStatusBadge`, `#consoleTerminalLogs`, `#consoleOutcomeDescription`, `c28SwitchConsoleTab`, `c28TriggerConsoleDeploy`), it will sit cleanly in its own dedicated section below the hero. This keeps the above-the-fold hero 100% uncluttered and faithful to the reference image while preserving all `hero.console_tabs` CMS functionality.
3. **Animation Asset Strategy (`public/media/hero-cubes.webp`)**:
   - **Alternative A (Rejected)**: Serving 300 individual `.jpg` files via JS frame-swapping (~300 HTTP requests, ~12 MB payload, layout/network jitter).
   - **Alternative B (Rejected)**: Converting to `.gif` (256-color palette banding, large file size).
   - **Alternative C (Recommended)**: Compiling the 300 frames into a single **animated WebP** (`public/media/hero-cubes.webp`) at ~30fps (`duration=33ms`, `loop=0`, `quality=80`, `method=4`) using Python Pillow `12.3.0`, plus a static first-frame poster (`public/media/hero-cubes-poster.jpg`) for `prefers-reduced-motion: reduce` via `<picture>` media query. Single HTTP request, 24-bit color, smooth playback, zero JS overhead.

---

## 4. Data, Storage, Security & Accessibility

- **Database / Migrations**: None required.
- **Security & Output Encoding**: All dynamic strings (`$heroBadge`, `$heroHeadline`, `$heroSubheadline`, `$primaryCtaLabel`) remain escaped via Blade `{{ ... }}`. No `{!! !!}` introduced.
- **Accessibility & Responsive Behavior**:
  - Desktop (`lg+`): 12-column / 50-50 split grid with hairline dividers and `+` crosshairs.
  - Mobile (`< lg`): Clean single-column stack (left copy + CTAs on top, animation panel below, metrics stacked cleanly).
  - Reduced Motion: `<picture>` element with `<source srcset="..." media="(prefers-reduced-motion: reduce)">` serves `hero-cubes-poster.jpg` for users with reduced-motion enabled.
  - Keyboard & Contrast: High-contrast `#F1F5F9` / `#FFFFFF` text on `#050806`, visible focus rings on all buttons and nav links.

---

## 5. Expected Files to Create / Modify

| Action | Path | Purpose |
| :--- | :--- | :--- |
| **Create** | `public/media/hero-cubes.webp` | Optimized 300-frame looping animated WebP generated from `ezgif-2756ed5923b649fb-jpg`. |
| **Create** | `public/media/hero-cubes-poster.jpg` | Static poster frame for `prefers-reduced-motion: reduce` fallback. |
| **Modify** | `resources/css/app.css` | Add `.bg-hero-hatch` diagonal pinstripe utility and architectural crosshair styling. |
| **Modify** | `resources/views/layouts/app.blade.php` | Update top navigation bar to the clean single-row slash-prefixed architectural header. |
| **Modify** | `resources/views/welcome.blade.php` | Replace hero section with the 50/50 architectural grid hero + EZGIF animation + bottom strip; place Control Plane Console in its own section below. |
| **Modify** | `tests/Feature/PublicFrontendTest.php` | Add assertions for the redesigned hero markup and `hero-cubes.webp` / `prefers-reduced-motion` poster. |
| **Create** | `docs/c28-web/phase-07-hero-redesign/02-implementation.md` | Stage D implementation record (after Stage B). |
| **Create** | `docs/c28-web/phase-07-hero-redesign/03-acceptance.md` | Stage D acceptance record (after Stage C). |

---

## 6. Test & Verification Plan

1. **Asset Verification**: Verify `public/media/hero-cubes.webp` and `public/media/hero-cubes-poster.jpg` exist, are non-empty, and `hero-cubes.webp` has 300 frames.
2. **Frontend Build**: Run `npm run build` and verify zero Vite/Tailwind compilation errors.
3. **Automated Test Suite**: Run `php artisan test` and verify all existing tests + new hero assertions pass.
4. **Browser Visual Check**: Inspect the rendered page in a live browser (if local server/browser is available) to verify alignment, crispness, and animation playback.

---

## 7. Risks, Assumptions & Open Questions

- **Assumption**: The user wants to keep C28's authentic branding, `SiteSetting` CMS headlines, and real engineering stack labels (Docker, GitHub Actions, Nginx TLS 1.3, Trivy) rather than the crypto/bridge copy (`Axiom Zero`, `Solana`, `TVL $1.72B`) shown in the reference screenshot.
- **Risk**: Animated WebP file size for 300 frames at 1280×720. If `quality=80` exceeds ~3–4 MB, we can resize to `960×540` or `quality=72` during Stage B to keep page load fast while preserving crispness.

---

> **Stage A complete — design review only. No implementation files were changed. Awaiting explicit approval to begin implementation.**
