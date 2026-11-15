@php
    $brandName = $settings['brand.name'] ?? 'C28 — Software & Cloud Engineering';
    $brandStatement = $settings['brand.statement'] ?? 'We build digital products and help teams deploy, secure, and operate them reliably.';
    $primaryCtaLabel = $settings['cta.primary_label'] ?? 'Book a Consultation';
    $contactEmail = $settings['contact.email'] ?? 'engineering@c28studios.com';
    $githubUrl = $settings['social.github_url'] ?? 'https://github.com/iam-borisjacobs/c28studios';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="C28 — Software & Cloud Engineering">
    <title>@yield('title', $brandName . ' | Deploy, Secure & Operate Reliably')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-obsidian-900 bg-carbon-grid text-slate-100 antialiased selection:bg-neon-500 selection:text-obsidian-950 min-h-screen flex flex-col">

    <!-- Architectural Navigation Header -->
    <header class="sticky top-0 z-50 bg-[#181818]/95 backdrop-blur-md border-b border-white/15">
        <div class="w-full px-4 sm:px-6 lg:px-10 xl:px-12">
            <div class="w-full px-4 sm:px-6 lg:px-10 h-16 flex items-center justify-between border-x border-white/15">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group" aria-label="{{ $brandName }} Home">
                <div class="w-7 h-7 rounded-[2px] bg-neon-accent flex items-center justify-center text-obsidian-950 font-mono font-bold text-xs tracking-tighter">
                    C28
                </div>
                <span class="text-white font-semibold text-base tracking-tight">C28 Studios</span>
            </a>

            <nav class="hidden lg:flex items-center gap-7 text-[11px] font-mono uppercase tracking-[0.14em] text-slate-400" aria-label="Primary Navigation">
                <a href="{{ route('home') }}#services" class="hover:text-white transition-colors"><span class="text-slate-600 mr-1.5">/</span>Services</a>
                <a href="{{ route('home') }}#blueprint" class="hover:text-white transition-colors"><span class="text-slate-600 mr-1.5">/</span>Blueprint</a>
                <a href="{{ route('home') }}#projects" class="hover:text-white transition-colors"><span class="text-slate-600 mr-1.5">/</span>Projects</a>
                <a href="{{ route('home') }}#engagement" class="hover:text-white transition-colors"><span class="text-slate-600 mr-1.5">/</span>Pricing</a>
                <a href="{{ route('home') }}#insights" class="hover:text-white transition-colors"><span class="text-slate-600 mr-1.5">/</span>Lab Notes</a>
            </nav>

            <div class="flex items-center gap-3">
                <button
                    type="button"
                    onclick="window.c28OpenModal && window.c28OpenModal(@js($primaryCtaLabel))"
                    class="border border-white/15 bg-obsidian-900 hover:border-neon-accent/60 hover:text-neon-accent text-slate-200 font-mono text-xs px-4 py-2 rounded-[2px] transition-colors"
                >
                    {{ $primaryCtaLabel }}
                </button>

                <button
                    type="button"
                    onclick="window.c28ToggleMobileNav && window.c28ToggleMobileNav()"
                    aria-label="Toggle Mobile Navigation"
                    class="lg:hidden p-2 rounded-[2px] border border-white/15 text-slate-300 hover:text-white"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
            </div>
        </div>

        <!-- Mobile Navigation Drawer -->
        <div id="c28MobileNav" class="hidden lg:hidden bg-obsidian-950 border-t border-white/10 px-4 py-4 space-y-2 text-xs font-mono uppercase tracking-wider text-slate-300">
            <a href="{{ route('home') }}#services" class="block py-2 hover:text-neon-accent">/ Services</a>
            <a href="{{ route('home') }}#blueprint" class="block py-2 hover:text-neon-accent">/ Blueprint</a>
            <a href="{{ route('home') }}#projects" class="block py-2 hover:text-neon-accent">/ Projects</a>
            <a href="{{ route('home') }}#engagement" class="block py-2 hover:text-neon-accent">/ Pricing</a>
            <a href="{{ route('home') }}#insights" class="block py-2 hover:text-neon-accent">/ Lab Notes</a>
            <a href="{{ route('home') }}#faq" class="block py-2 hover:text-neon-accent">/ FAQ</a>
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
                        <div class="w-8 h-8 rounded-lg btn-emerald flex items-center justify-center text-obsidian-950 font-extrabold text-xs">
                            C28
                        </div>
                        <span class="text-white font-bold text-lg tracking-tight">{{ $brandName }}</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-400 max-w-sm leading-relaxed mb-4">
                        {{ $brandStatement }}
                    </p>
                    <div class="text-xs font-mono text-neon-400">
                        &#9679; {{ $contactEmail }}
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
                        <li><a href="{{ url('/admin') }}" class="font-mono text-neon-400 hover:text-neon-accent transition">Admin Control Plane &rarr;</a></li>
                    </ul>
                </div>
            </div>

            <div class="py-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} {{ $brandName }}. All rights reserved.</p>
                <p class="mt-2 sm:mt-0 font-mono">Built for reliability from localhost to production.</p>
            </div>
        </div>

        <div class="select-none pointer-events-none text-center overflow-hidden leading-none pt-2 -mb-6 sm:-mb-10">
            <span class="text-[18vw] font-extrabold tracking-tighter text-transparent bg-clip-text bg-gradient-to-b from-emerald-500/20 via-emerald-500/5 to-transparent">
                C28Studios
            </span>
        </div>
    </footer>

    <!-- Interactive Consultation / Architecture Scoping Modal -->
    <div id="consultationModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-obsidian-950/85 backdrop-blur-md" role="dialog" aria-modal="true" aria-labelledby="consultationModalTitle">
        <div class="glass-card rounded-3xl max-w-lg w-full p-6 sm:p-8 border border-neon-400/40 shadow-2xl relative">
            <button type="button" onclick="window.c28CloseModal && window.c28CloseModal()" aria-label="Close modal" class="absolute top-5 right-5 text-slate-400 hover:text-white text-xl font-mono">&times;</button>
            <span id="modalBadge" class="inline-block px-3 py-1 rounded-full text-[11px] font-mono uppercase tracking-widest text-neon-400 section-badge mb-3">
                C28 ENGINEERING INQUIRY
            </span>
            <h3 id="consultationModalTitle" class="text-2xl font-extrabold text-white mb-2">Scope Your Project With C28</h3>
            <p class="text-xs sm:text-sm text-slate-300 mb-6">
                Tell us which service you need and where your project stands today. You'll work directly with our engineering studio.
            </p>

            <form id="modalForm" method="POST" action="{{ route('inquiries.store') }}" onsubmit="window.c28SubmitModal && window.c28SubmitModal(event)" class="space-y-4">
                @csrf

                <!-- Honeypot anti-spam trap -->
                <div class="hidden" aria-hidden="true">
                    <label for="modalWebsiteUrlInput">Website URL</label>
                    <input id="modalWebsiteUrlInput" name="website_url" type="text" tabindex="-1" autocomplete="off" value="">
                </div>

                <div id="modalErrors" class="hidden p-3.5 rounded-xl bg-rose-500/15 border border-rose-500/40 text-xs text-rose-200 space-y-1" role="alert"></div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-mono uppercase text-slate-300 mb-1.5" for="modalNameInput">Your Name &amp; Role *</label>
                        <input id="modalNameInput" name="name" type="text" required placeholder="e.g. Alex Rivera — SaaS Founder" class="w-full px-4 py-2.5 rounded-xl bg-obsidian-950 border border-emerald-500/25 text-sm text-white focus:outline-none focus:border-neon-400">
                    </div>
                    <div>
                        <label class="block text-xs font-mono uppercase text-slate-300 mb-1.5" for="modalEmailInput">Work Email *</label>
                        <input id="modalEmailInput" name="email" type="email" required placeholder="founder@company.com" class="w-full px-4 py-2.5 rounded-xl bg-obsidian-950 border border-emerald-500/25 text-sm text-white focus:outline-none focus:border-neon-400">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-mono uppercase text-slate-300 mb-1.5" for="modalCompanyInput">Company / Project</label>
                        <input id="modalCompanyInput" name="company" type="text" placeholder="Optional" class="w-full px-4 py-2.5 rounded-xl bg-obsidian-950 border border-emerald-500/25 text-sm text-white focus:outline-none focus:border-neon-400">
                    </div>
                    <div>
                        <label class="block text-xs font-mono uppercase text-slate-300 mb-1.5" for="modalEngagementMode">Engagement Mode</label>
                        <select id="modalEngagementMode" name="engagement_mode" class="w-full px-4 py-2.5 rounded-xl bg-obsidian-950 border border-emerald-500/25 text-sm text-white focus:outline-none focus:border-neon-400">
                            <option value="sprint_fixed">Fixed-Scope Sprint</option>
                            <option value="monthly_retainer">Monthly Retainer</option>
                            <option value="architecture_review">Architecture Review</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1.5" for="modalServiceSelect">Primary Engineering Need *</label>
                    <select id="modalServiceSelect" name="service_interest" class="w-full px-4 py-2.5 rounded-xl bg-obsidian-950 border border-emerald-500/25 text-sm text-white focus:outline-none focus:border-neon-400">
                        <option value="DevOps & Cloud Engineering">01 • DevOps &amp; Cloud Engineering (CI/CD, Docker, Server Setup)</option>
                        <option value="DevSecOps Integration">02 • DevSecOps Integration (Vulnerability Scanning, Secret Management)</option>
                        <option value="Software Development">03 • Software Development (SaaS, Web App, API, Dashboard)</option>
                        <option value="Website Development">04 • Website Development (Landing Page, Business Site, Technical SEO)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-mono uppercase text-slate-300 mb-1.5" for="modalNotes">Repository or Architecture Context *</label>
                    <textarea id="modalNotes" name="message" rows="3" required placeholder="Briefly describe your application, current deployment process, or timeline..." class="w-full px-4 py-2.5 rounded-xl bg-obsidian-950 border border-emerald-500/25 text-sm text-white focus:outline-none focus:border-neon-400"></textarea>
                </div>
                <button id="modalSubmitBtn" type="submit" class="btn-emerald w-full py-3.5 rounded-full text-obsidian-950 font-bold text-sm">
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
