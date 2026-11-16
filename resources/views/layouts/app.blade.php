@php
    $brandName = $settings['brand.name'] ?? 'C28 — Software & Cloud Engineering';
    $brandStatement = $settings['brand.statement'] ?? 'We build digital products and help teams deploy, secure, and operate them reliably.';
    $logoTop = trim((string) ($settings['brand.logo_top'] ?? ''));
    $logoBottom = trim((string) ($settings['brand.logo_bottom'] ?? ''));
    $logoText = trim((string) ($settings['brand.logo_text'] ?? 'C28')) ?: 'C28';
    $defaultTheme = in_array(($settings['theme.mode'] ?? 'system'), ['dark', 'light', 'system'], true)
        ? $settings['theme.mode']
        : 'system';
    $resolveLogoUrl = static function (string $path): string {
        if ($path === '') {
            return '';
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/')) {
            return $path;
        }
        return asset('storage/' . ltrim($path, '/'));
    };
    $logoTopUrl = $resolveLogoUrl($logoTop);
    $logoBottomUrl = $resolveLogoUrl($logoBottom);
    $primaryCtaLabel = $settings['cta.primary_label'] ?? 'Book a Consultation';
    $contactEmail = $settings['contact.email'] ?? 'contact@c28studios.dev';
    $contactPhone = $settings['contact.phone'] ?? '803 3880 229';
    $contactWebsite = $settings['contact.website'] ?? 'www.c28studios.dev';
    $contactWebsiteHref = (str_starts_with($contactWebsite, 'http://') || str_starts_with($contactWebsite, 'https://'))
        ? $contactWebsite
        : 'https://' . ltrim($contactWebsite, '/');
    $rawGithubUrl = trim((string) ($settings['social.github_url'] ?? 'https://github.com/iam-borisjacobs/c28studios'));
    $githubUrl = (str_starts_with($rawGithubUrl, 'http://') || str_starts_with($rawGithubUrl, 'https://'))
        ? $rawGithubUrl
        : 'https://github.com/iam-borisjacobs/c28studios';
    $defaultMetaDescription = $brandStatement . ' Partner with C28 for DevOps & Cloud Engineering, DevSecOps Integration, SaaS Software Development, and high-conversion Website Development.';
    $defaultOgImage = $logoTopUrl !== '' ? $logoTopUrl : asset('media/hero-frames/ezgif-frame-001.jpg');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-default-theme="{{ $defaultTheme }}" class="scroll-smooth {{ $defaultTheme === 'light' ? 'theme-light' : 'dark' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="C28 — Software & Cloud Engineering">
    <title>@yield('title', $brandName . ' | Deploy, Secure & Operate Reliably')</title>
    <meta name="description" content="@yield('meta_description', $defaultMetaDescription)">
    <meta name="keywords" content="@yield('meta_keywords', 'C28 Studios, C28 Software and Cloud Engineering, DevOps Engineering, Cloud Engineering, CI/CD Pipelines, GitHub Actions, Docker Containerization, DevSecOps Integration, Trivy Security Scanning, Linux Server Hardening, Nginx Reverse Proxy, SaaS Software Development, Website Development, Technical SEO')">
    <meta name="robots" content="@yield('meta_robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1')">
    <link rel="canonical" href="@yield('canonical_url', url()->current())">
    <link rel="alternate" type="text/markdown" href="{{ url('/llms.txt') }}" title="{{ $brandName }} — AI &amp; LLM Discovery Summary">

    <!-- OpenGraph Metadata for Social & AI Previews -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $brandName }}">
    <meta property="og:title" content="@yield('title', $brandName . ' | Deploy, Secure & Operate Reliably')">
    <meta property="og:description" content="@yield('meta_description', $defaultMetaDescription)">
    <meta property="og:url" content="@yield('canonical_url', url()->current())">
    <meta property="og:image" content="@yield('og_image', $defaultOgImage)">
    <meta property="og:locale" content="en_US">

    <!-- Twitter / X Card Metadata -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', $brandName . ' | Deploy, Secure & Operate Reliably')">
    <meta name="twitter:description" content="@yield('meta_description', $defaultMetaDescription)">
    <meta name="twitter:image" content="@yield('og_image', $defaultOgImage)">

    @stack('structured_data')

    <script>
        (function () {
            try {
                var saved = sessionStorage.getItem('c28_theme_override');
                var defaultTheme = @js($defaultTheme);
                var systemLight = window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches;
                var mode;
                if (saved === 'light' || saved === 'dark') {
                    mode = saved;
                } else if (defaultTheme === 'light') {
                    mode = 'light';
                } else {
                    mode = systemLight ? 'light' : 'dark';
                }
                var root = document.documentElement;
                if (mode === 'light') {
                    root.classList.add('theme-light');
                    root.classList.remove('dark');
                } else {
                    root.classList.add('dark');
                    root.classList.remove('theme-light');
                }
            } catch (e) {}
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-obsidian-900 bg-carbon-grid text-slate-100 antialiased selection:bg-neon-500 selection:text-obsidian-950 min-h-screen flex flex-col">

    <!-- Architectural Navigation Header -->
    <header class="sticky top-0 z-50 bg-[#181818]/95 backdrop-blur-md border-b border-white/15">
        <div class="w-full px-3 sm:px-6 lg:px-10 xl:px-12">
            <div class="w-full px-3.5 sm:px-6 lg:px-10 h-16 flex items-center justify-between gap-2 border-x border-white/15">
                <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-2.5 group min-w-0 shrink" aria-label="{{ $brandName }} Home">
                    @if($logoTopUrl !== '')
                        <img src="{{ $logoTopUrl }}" alt="{{ $brandName }} Logo" class="h-7 sm:h-8 w-auto object-contain shrink-0">
                    @else
                        <div class="w-7 h-7 rounded-[2px] bg-neon-accent flex items-center justify-center text-obsidian-950 font-mono font-bold text-xs tracking-tighter shrink-0">
                            {{ $logoText }}
                        </div>
                    @endif
                    <span class="text-white font-semibold text-sm sm:text-base tracking-tight truncate">{{ $brandName }}</span>
                </a>

                <nav class="hidden lg:flex items-center gap-7 text-[11px] font-mono uppercase tracking-[0.14em] text-slate-400" aria-label="Primary Navigation">
                    <a href="{{ route('home') }}#services" class="hover:text-white transition-colors"><span class="text-slate-600 mr-1.5">/</span>Services</a>
                    <a href="{{ route('home') }}#blueprint" class="hover:text-white transition-colors"><span class="text-slate-600 mr-1.5">/</span>Blueprint</a>
                    <a href="{{ route('home') }}#projects" class="hover:text-white transition-colors"><span class="text-slate-600 mr-1.5">/</span>Projects</a>
                    <a href="{{ route('home') }}#engagement" class="hover:text-white transition-colors"><span class="text-slate-600 mr-1.5">/</span>Pricing</a>
                    <a href="{{ route('home') }}#insights" class="hover:text-white transition-colors"><span class="text-slate-600 mr-1.5">/</span>Lab Notes</a>
                </nav>

                <div class="flex items-center gap-2 shrink-0">
                    <button
                        type="button"
                        id="c28ThemeToggle"
                        onclick="window.c28ToggleTheme && window.c28ToggleTheme()"
                        aria-label="Toggle Dark or White Theme"
                        title="Switch Theme (Dark / White)"
                        class="border border-white/15 bg-obsidian-900 hover:border-neon-accent/60 hover:text-neon-accent text-slate-200 font-mono text-[11px] sm:text-xs px-2.5 sm:px-3 py-2 rounded-[2px] transition-colors inline-flex items-center gap-1 whitespace-nowrap shrink-0"
                    >
                        <span id="c28ThemeToggleLabel">Theme</span>
                    </button>

                    <button
                        type="button"
                        onclick="window.c28OpenModal && window.c28OpenModal(@js($primaryCtaLabel))"
                        class="hidden sm:inline-flex items-center justify-center border border-white/15 bg-obsidian-900 hover:border-neon-accent/60 hover:text-neon-accent text-slate-200 font-mono text-xs px-4 py-2 rounded-[2px] transition-colors whitespace-nowrap shrink-0"
                    >
                        {{ $primaryCtaLabel }}
                    </button>

                    <button
                        type="button"
                        onclick="window.c28ToggleMobileNav && window.c28ToggleMobileNav()"
                        aria-label="Toggle Mobile Navigation"
                        class="lg:hidden p-2 rounded-[2px] border border-white/15 bg-obsidian-900 text-slate-300 hover:text-white shrink-0"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Drawer -->
        <div id="c28MobileNav" class="hidden lg:hidden bg-obsidian-950 border-t border-white/15 px-5 py-4 space-y-2.5 text-xs font-mono uppercase tracking-wider text-slate-300">
            <a href="{{ route('home') }}#services" onclick="window.c28ToggleMobileNav && window.c28ToggleMobileNav()" class="block py-2 hover:text-neon-accent">/ Services</a>
            <a href="{{ route('home') }}#blueprint" onclick="window.c28ToggleMobileNav && window.c28ToggleMobileNav()" class="block py-2 hover:text-neon-accent">/ Blueprint</a>
            <a href="{{ route('home') }}#projects" onclick="window.c28ToggleMobileNav && window.c28ToggleMobileNav()" class="block py-2 hover:text-neon-accent">/ Projects</a>
            <a href="{{ route('home') }}#engagement" onclick="window.c28ToggleMobileNav && window.c28ToggleMobileNav()" class="block py-2 hover:text-neon-accent">/ Pricing</a>
            <a href="{{ route('home') }}#insights" onclick="window.c28ToggleMobileNav && window.c28ToggleMobileNav()" class="block py-2 hover:text-neon-accent">/ Lab Notes</a>
            <a href="{{ route('home') }}#faq" onclick="window.c28ToggleMobileNav && window.c28ToggleMobileNav()" class="block py-2 hover:text-neon-accent">/ FAQ</a>
            <div class="pt-2 border-t border-white/10 sm:hidden">
                <button
                    type="button"
                    onclick="window.c28ToggleMobileNav && window.c28ToggleMobileNav(); window.c28OpenModal && window.c28OpenModal(@js($primaryCtaLabel))"
                    class="w-full py-3 bg-neon-accent text-obsidian-950 font-mono font-bold text-xs uppercase tracking-wider rounded-[2px]"
                >
                    {{ $primaryCtaLabel }}
                </button>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer with Giant Cropped Watermark -->
    <footer class="pt-16 bg-obsidian-950 border-t border-emerald-500/15 relative overflow-hidden">
        <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-20">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 pb-14 border-b border-emerald-500/10">
                <div class="col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        @if($logoBottomUrl !== '')
                            <img src="{{ $logoBottomUrl }}" alt="{{ $brandName }} Logo" class="h-8 w-auto object-contain">
                        @else
                            <div class="w-8 h-8 rounded-lg btn-emerald flex items-center justify-center text-obsidian-950 font-extrabold text-xs">
                                {{ $logoText }}
                            </div>
                        @endif
                        <span class="text-white font-bold text-lg tracking-tight">{{ $brandName }}</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-400 max-w-sm leading-relaxed mb-4">
                        {{ $brandStatement }}
                    </p>
                    <div class="space-y-1.5 text-xs font-mono text-neon-400">
                        <div>&#9679; <a href="mailto:{{ $contactEmail }}" class="hover:underline">{{ $contactEmail }}</a></div>
                        <div>&#9679; <a href="tel:{{ preg_replace('/\s+/', '', $contactPhone) }}" class="hover:underline">{{ $contactPhone }}</a></div>
                        <div>&#9679; <a href="{{ $contactWebsiteHref }}" target="_blank" rel="noopener" class="hover:underline">{{ $contactWebsite }}</a></div>
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-mono uppercase tracking-wider text-white mb-4">Service Hierarchy</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><a href="{{ route('home') }}#services" class="hover:text-neon-400 transition">01 • DevOps &amp; Cloud</a></li>
                        <li><a href="{{ route('home') }}#services" class="hover:text-neon-400 transition">02 • DevSecOps Integration</a></li>
                        <li><a href="{{ route('home') }}#services" class="hover:text-neon-400 transition">03 • Software Development</a></li>
                        <li><a href="{{ route('home') }}#services" class="hover:text-neon-400 transition">04 • Website Development</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-mono uppercase tracking-wider text-white mb-4">Architecture</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><a href="{{ route('home') }}#blueprint" class="hover:text-neon-400 transition">10-Step Blueprint</a></li>
                        <li><a href="{{ route('home') }}#projects" class="hover:text-neon-400 transition">Case Studies &amp; Lab</a></li>
                        <li><a href="{{ route('home') }}#insights" class="hover:text-neon-400 transition">Engineering Walkthroughs</a></li>
                        <li><a href="{{ $githubUrl }}" target="_blank" rel="noopener" class="hover:text-neon-400 transition">GitHub Repository</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-mono uppercase tracking-wider text-white mb-4">Control &amp; Scope</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><button type="button" onclick="window.c28OpenModal && window.c28OpenModal('Footer Consultation')" class="hover:text-neon-400 transition">Book a Consultation</button></li>
                        <li><a href="{{ route('home') }}#engagement" class="hover:text-neon-400 transition">Sprint &amp; Retainer Tiers</a></li>
                        <li><a href="{{ route('home') }}#faq" class="hover:text-neon-400 transition">Founder &amp; Technical FAQ</a></li>
                        <li><a href="{{ route('home') }}#consultation" class="hover:text-neon-400 transition">Scope Your Project</a></li>
                    </ul>
                </div>
            </div>

            <div class="py-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} {{ $brandName }}. All rights reserved.</p>
                <p class="mt-2 sm:mt-0 font-mono">Built for reliability from localhost to production.</p>
            </div>
        </div>

        <div class="select-none pointer-events-none text-center overflow-hidden leading-none pt-2 -mb-6 sm:-mb-10">
            <span class="text-[12vw] sm:text-[14vw] font-extrabold tracking-tighter text-transparent bg-clip-text bg-gradient-to-b from-emerald-500/20 via-emerald-500/5 to-transparent">
                {{ $brandName }}
            </span>
        </div>
    </footer>

    <!-- Bottom-to-Top Floating Button -->
    <button
        type="button"
        id="c28BackToTop"
        onclick="window.c28ScrollToTop && window.c28ScrollToTop()"
        aria-label="Scroll back to top"
        title="Back to top"
        class="fixed bottom-6 right-6 z-40 hidden items-center justify-center w-11 h-11 rounded-[2px] border border-white/20 bg-obsidian-900/95 hover:border-neon-accent text-neon-accent shadow-xl transition-all"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
        </svg>
    </button>

    <!-- Interactive Consultation / Architecture Scoping Modal -->
    <div
        id="consultationModal"
        onclick="if (event.target === this && window.c28CloseModal) window.c28CloseModal()"
        class="fixed inset-0 z-50 hidden overflow-y-auto items-start sm:items-center justify-center p-3 sm:p-6 bg-black/75 backdrop-blur-md"
        role="dialog"
        aria-modal="true"
        aria-labelledby="consultationModalTitle"
    >
        <div class="glass-card my-auto max-h-[calc(100dvh-1.5rem)] sm:max-h-[90dvh] overflow-y-auto rounded-2xl sm:rounded-3xl max-w-lg w-full p-5 sm:p-8 border border-neon-400/40 shadow-2xl relative">
            <div class="flex items-start justify-between gap-3 mb-2">
                <span id="modalBadge" class="inline-block px-3 py-1 rounded-full text-[10px] sm:text-[11px] font-mono uppercase tracking-widest text-neon-400 section-badge">
                    C28 ENGINEERING INQUIRY
                </span>
                <button
                    type="button"
                    onclick="window.c28CloseModal && window.c28CloseModal()"
                    aria-label="Close modal"
                    class="w-8 h-8 rounded-lg border border-white/15 bg-obsidian-900 text-slate-300 hover:text-white flex items-center justify-center text-lg font-mono shrink-0"
                >
                    &times;
                </button>
            </div>
            <h3 id="consultationModalTitle" class="text-xl sm:text-2xl font-extrabold text-white mb-1.5">Scope Your Project With C28</h3>
            <p class="text-xs sm:text-sm text-slate-300 mb-4 sm:mb-6 leading-relaxed">
                Tell us which service you need and where your project stands today. You'll work directly with our engineering studio.
            </p>

            <form id="modalForm" method="POST" action="{{ route('inquiries.store', [], false) }}" onsubmit="window.c28SubmitModal && window.c28SubmitModal(event)" class="space-y-3 sm:space-y-4">
                @csrf

                <!-- Honeypot anti-spam trap -->
                <div class="hidden" aria-hidden="true">
                    <label for="modalWebsiteUrlInput">Website URL</label>
                    <input id="modalWebsiteUrlInput" name="website_url" type="text" tabindex="-1" autocomplete="off" value="">
                </div>

                <div id="modalErrors" class="hidden p-3.5 rounded-xl bg-rose-500/15 border border-rose-500/40 text-xs text-rose-200 space-y-1" role="alert"></div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] sm:text-xs font-mono uppercase text-slate-300 mb-1" for="modalNameInput">Your Name &amp; Role *</label>
                        <input id="modalNameInput" name="name" type="text" required placeholder="e.g. Alex Rivera — SaaS Founder" class="w-full px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl bg-obsidian-950 border border-emerald-500/25 text-xs sm:text-sm text-white focus:outline-none focus:border-neon-400">
                    </div>
                    <div>
                        <label class="block text-[11px] sm:text-xs font-mono uppercase text-slate-300 mb-1" for="modalEmailInput">Work Email *</label>
                        <input id="modalEmailInput" name="email" type="email" required placeholder="founder@company.com" class="w-full px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl bg-obsidian-950 border border-emerald-500/25 text-xs sm:text-sm text-white focus:outline-none focus:border-neon-400">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] sm:text-xs font-mono uppercase text-slate-300 mb-1" for="modalCompanyInput">Company / Project</label>
                        <input id="modalCompanyInput" name="company" type="text" placeholder="Optional" class="w-full px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl bg-obsidian-950 border border-emerald-500/25 text-xs sm:text-sm text-white focus:outline-none focus:border-neon-400">
                    </div>
                    <div>
                        <label class="block text-[11px] sm:text-xs font-mono uppercase text-slate-300 mb-1" for="modalEngagementMode">Engagement Mode</label>
                        <select id="modalEngagementMode" name="engagement_mode" class="c28-select w-full px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl bg-obsidian-950 border border-emerald-500/25 text-xs sm:text-sm text-white focus:outline-none focus:border-neon-400">
                            <option value="sprint_fixed" data-subtitle="Defined deliverables &amp; milestone timeline">Fixed-Scope Sprint</option>
                            <option value="monthly_retainer" data-subtitle="Ongoing DevOps, security &amp; feature velocity">Monthly Retainer</option>
                            <option value="architecture_review" data-subtitle="Repository, server &amp; pipeline audit">Architecture Review</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] sm:text-xs font-mono uppercase text-slate-300 mb-1" for="modalServiceSelect">Primary Engineering Need *</label>
                    <select id="modalServiceSelect" name="service_interest" class="c28-select w-full px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl bg-obsidian-950 border border-emerald-500/25 text-xs sm:text-sm text-white focus:outline-none focus:border-neon-400">
                        <option value="DevOps & Cloud Engineering" data-subtitle="CI/CD, Docker, Linux &amp; Cloud Setup">01 • DevOps &amp; Cloud Engineering</option>
                        <option value="DevSecOps Integration" data-subtitle="Trivy Scanning, Secrets &amp; Security Gates">02 • DevSecOps Integration</option>
                        <option value="Software Development" data-subtitle="SaaS, Web Apps, APIs &amp; Dashboards">03 • Software Development</option>
                        <option value="Website Development" data-subtitle="Landing Pages, Business Sites &amp; Technical SEO">04 • Website Development</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] sm:text-xs font-mono uppercase text-slate-300 mb-1" for="modalNotes">Repository or Architecture Context *</label>
                    <textarea id="modalNotes" name="message" rows="3" required placeholder="Briefly describe your application, current deployment process, or timeline..." class="w-full px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl bg-obsidian-950 border border-emerald-500/25 text-xs sm:text-sm text-white focus:outline-none focus:border-neon-400"></textarea>
                </div>
                <button id="modalSubmitBtn" type="submit" class="btn-emerald w-full py-3 sm:py-3.5 rounded-full text-obsidian-950 font-bold text-xs sm:text-sm">
                    Send Engineering Request
                </button>
            </form>

            <div id="modalSuccess" class="hidden text-center py-6 space-y-3">
                <div class="w-12 h-12 rounded-full bg-emerald-500/20 border border-neon-400 text-neon-400 flex items-center justify-center mx-auto text-xl font-bold">
                    &#10003;
                </div>
                <h4 class="text-lg font-bold text-white">Inquiry Logged</h4>
                <p class="text-xs sm:text-sm text-slate-300 max-w-sm mx-auto">
                    Thank you! Reach us directly at <span class="font-mono text-neon-400">{{ $contactEmail }}</span> or we will follow up within 1 business day.
                </p>
                <button type="button" onclick="window.c28CloseModal && window.c28CloseModal()" class="mt-2 px-6 py-2 rounded-full text-xs font-mono bg-white/10 text-white hover:bg-white/20">
                    Return to C28
                </button>
            </div>
        </div>
    </div>
</body>
</html>
