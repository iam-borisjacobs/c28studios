@extends('layouts.app')

@php
    $heroBadge = $settings['hero.badge'] ?? 'C28 — SOFTWARE & CLOUD ENGINEERING';
    $heroHeadline = $settings['hero.headline'] ?? 'We Build Digital Products & Help Teams Deploy, Secure, and Operate Them Reliably.';
    $heroSubheadline = $settings['hero.subheadline'] ?? 'Instead of hiring separate people for application code, server configuration, CI/CD automation, and security, partner with one connected engineering studio built for startup founders, small SaaS teams, and growing businesses.';
    $brandStatement = $settings['brand.statement'] ?? 'We build digital products and help teams deploy, secure, and operate them reliably.';
    $consoleTabs = (array) ($settings['hero.console_tabs'] ?? []);
    $firstTabKey = array_key_first($consoleTabs) ?? 'pipeline';
    $firstTab = $consoleTabs[$firstTabKey] ?? ['logs' => [], 'outcome' => ''];
    $blueprintSteps = (array) ($settings['blueprint.steps'] ?? []);
    $activeBpIndex = isset($blueprintSteps[2]) ? 2 : 0;
    $activeBp = $blueprintSteps[$activeBpIndex] ?? ($blueprintSteps[0] ?? []);
    $scenarios = (array) ($settings['scenarios.items'] ?? []);
    $firstArtifact = $codeArtifacts[0] ?? ['title' => '.github/workflows/deploy-production.yml', 'code' => ''];
@endphp

@section('content')
    <!-- SECTION 01: ARCHITECTURAL HERO & EZGIF SYSTEM VISUALIZATION -->
    <section class="bg-[#181818] border-b border-white/15">
        <div class="w-full px-3 sm:px-6 lg:px-10 xl:px-12">
        <div class="w-full border-x border-white/15 relative">
            <!-- Top Intersection Crosshairs -->
            <span aria-hidden="true" class="hidden lg:block absolute -top-2.5 -left-1.5 font-mono text-xs text-white/40 select-none pointer-events-none">+</span>
            <span aria-hidden="true" class="hidden lg:block absolute -top-2.5 left-1/2 -translate-x-1/2 font-mono text-xs text-white/40 select-none pointer-events-none">+</span>
            <span aria-hidden="true" class="hidden lg:block absolute -top-2.5 -right-1.5 font-mono text-xs text-white/40 select-none pointer-events-none">+</span>

            <!-- Primary 50/50 Split Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 border-b border-white/15">
                <!-- Left Cell: Headline, Subheadline & Sharp CTAs -->
                <div class="lg:col-span-6 bg-hero-hatch px-6 py-10 sm:px-10 sm:py-14 lg:px-14 lg:py-16 xl:px-16 flex flex-col justify-center border-b lg:border-b-0 lg:border-r border-white/15">
                    <div class="inline-flex items-center gap-2 px-3 py-1 border border-white/20 bg-[#181818]/90 text-[11px] font-mono text-slate-300 tracking-wide w-fit mb-7">
                        <span>{{ $heroBadge }}</span>
                        <span class="inline-block w-1.5 h-3 bg-neon-accent/90" aria-hidden="true"></span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl lg:text-4xl xl:text-[2.5rem] font-bold text-white tracking-[-0.025em] leading-[1.12] mb-5">
                        {{ $heroHeadline }}
                    </h1>

                    <p class="text-sm sm:text-base text-slate-300 leading-relaxed mb-8 max-w-2xl">
                        {{ $heroSubheadline }}
                    </p>

                    <div class="flex flex-wrap items-center gap-3">
                        <a
                            href="#services"
                            class="px-6 py-3.5 border border-white/20 bg-[#181818] hover:border-white/40 text-xs font-mono text-slate-200 transition-colors rounded-[2px]"
                        >
                            Explore Services
                        </a>
                        <button
                            type="button"
                            onclick="window.c28OpenModal && window.c28OpenModal('Architecture Review & Scope')"
                            class="px-7 py-3.5 bg-neon-accent hover:bg-neon-400 text-obsidian-950 font-bold text-xs font-mono tracking-wide transition-colors rounded-[2px]"
                        >
                            {{ $settings['cta.primary_label'] ?? 'Book Consultation' }}
                        </button>
                    </div>
                </div>

                <!-- Right Cell: EZGIF 235-Frame 3D Dotted Dual-Cube Animation -->
                <div class="lg:col-span-6 bg-hero-cube-grid relative flex items-center justify-center overflow-hidden min-h-[340px] sm:min-h-[440px] lg:min-h-[540px] xl:min-h-[600px]">
                    <!-- Top Telemetry Bar matching reference -->
                    <div class="absolute top-4 inset-x-4 z-10 flex items-center justify-between pointer-events-none">
                        <span class="px-2.5 py-1 bg-[#181818]/90 border border-neon-500/35 text-[10px] font-mono uppercase tracking-[0.16em] text-neon-accent">
                            ALL SYSTEMS OPERATIONAL
                        </span>
                        <div class="hidden sm:flex items-center gap-1 px-2 py-1 bg-[#181818]/90 border border-neon-500/30" aria-hidden="true">
                            @for($bar = 0; $bar < 14; $bar++)
                                <span class="w-1 h-3 {{ $bar < 10 ? 'bg-neon-accent/85' : 'bg-white/20' }}"></span>
                            @endfor
                        </div>
                    </div>

                    <img
                        id="heroEzgifFallback"
                        src="{{ ($settings['theme.mode'] ?? 'dark') === 'light' ? asset('media/hero-frames-light/ezgif-frame-001.jpg') : asset('media/hero-frames/ezgif-frame-001.jpg') }}"
                        data-src-dark="{{ asset('media/hero-frames/ezgif-frame-001.jpg') }}"
                        data-src-light="{{ asset('media/hero-frames-light/ezgif-frame-001.jpg') }}"
                        alt="C28 Systems Operational 3D Telemetry Animation"
                        width="1086"
                        height="1078"
                        loading="eager"
                        decoding="async"
                        class="w-full h-full max-h-[580px] object-contain object-center block hero-ezgif-media"
                    >
                    <canvas
                        id="heroEzgifCanvas"
                        width="1066"
                        height="1058"
                        data-base-url="{{ asset('media/hero-frames') }}"
                        data-base-url-dark="{{ asset('media/hero-frames') }}"
                        data-base-url-light="{{ asset('media/hero-frames-light') }}"
                        data-frame-count="235"
                        aria-label="C28 Systems Operational 3D Telemetry Animation"
                        class="hidden w-full h-full max-h-[580px] object-contain object-center hero-ezgif-media"
                    ></canvas>
                </div>
            </div>

            <!-- Bottom 50/50 Strip: Engineering Stack & Verifiable Metrics -->
            <div class="grid grid-cols-1 lg:grid-cols-12 relative bg-[#181818]">
                <span aria-hidden="true" class="hidden lg:block absolute -bottom-2.5 -left-1.5 font-mono text-xs text-white/40 select-none pointer-events-none">+</span>
                <span aria-hidden="true" class="hidden lg:block absolute -bottom-2.5 left-1/2 -translate-x-1/2 font-mono text-xs text-white/40 select-none pointer-events-none">+</span>
                <span aria-hidden="true" class="hidden lg:block absolute -bottom-2.5 -right-1.5 font-mono text-xs text-white/40 select-none pointer-events-none">+</span>

                <!-- Bottom-Left: Authentic Monochrome Engineering Stack -->
                <div class="lg:col-span-6 px-6 sm:px-10 lg:px-14 py-6 border-b lg:border-b-0 lg:border-r border-white/15 flex flex-wrap items-center justify-between gap-x-6 gap-y-3 text-slate-400 font-mono text-xs tracking-wider uppercase">
                    <span class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-slate-400 inline-block"></span>
                        DOCKER
                    </span>
                    <span class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-slate-400 inline-block"></span>
                        GITHUB ACTIONS
                    </span>
                    <span class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-slate-400 inline-block"></span>
                        NGINX TLS 1.3
                    </span>
                    <span class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-slate-400 inline-block"></span>
                        TRIVY
                    </span>
                </div>

                <!-- Bottom-Right: 3-Column Telemetry / Architecture Strip -->
                <div class="lg:col-span-6 grid grid-cols-3 divide-x divide-white/15">
                    <div class="px-4 sm:px-7 py-5">
                        <div class="text-[10px] font-mono uppercase tracking-widest text-slate-500 mb-1.5">
                            Core Disciplines
                        </div>
                        <div class="text-xl sm:text-2xl font-mono font-semibold text-white">
                            04<span class="text-neon-accent">_</span>
                        </div>
                    </div>
                    <div class="px-4 sm:px-7 py-5">
                        <div class="text-[10px] font-mono uppercase tracking-widest text-slate-500 mb-1.5">
                            Release Downtime
                        </div>
                        <div class="text-xl sm:text-2xl font-mono font-semibold text-white">
                            0m<span class="text-neon-accent">_</span>
                        </div>
                    </div>
                    <div class="px-4 sm:px-7 py-5">
                        <div class="text-[10px] font-mono uppercase tracking-widest text-slate-500 mb-1.5">
                            Security Gates
                        </div>
                        <div class="text-xl sm:text-2xl font-mono font-semibold text-white">
                            100%<span class="text-neon-accent">_</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- SECTION 02: INTERACTIVE LIVE CONTROL PLANE CONSOLE -->
    <section class="py-16 bg-obsidian-900 border-b border-white/10">
        <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-20">
            <div class="relative">
                <div class="glass-card rounded-2xl border border-emerald-500/30 overflow-hidden shadow-2xl">
                    <div class="bg-obsidian-950/90 border-b border-emerald-500/15 px-4 sm:px-6 py-3.5 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500/80"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-400/80"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-neon-400"></span>
                            </div>
                            <span class="text-xs font-mono text-slate-300 flex items-center gap-2">
                                <span class="text-neon-400 font-semibold">c28-control-plane</span>
                                <span class="text-slate-600">/</span>
                                <span>production-delivery-pipeline.yml</span>
                            </span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span id="consoleStatusBadge" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-mono bg-emerald-500/15 text-neon-400 border border-emerald-500/30">
                                PRODUCTION HEALTHY • 99.98% UPTIME
                            </span>
                            <button type="button" onclick="window.c28TriggerConsoleDeploy && window.c28TriggerConsoleDeploy()" class="text-xs font-mono bg-emerald-500/20 hover:bg-emerald-500/30 text-neon-400 border border-emerald-500/40 px-3 py-1 rounded-md transition">
                                &#9654; Simulate Push
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[360px]">
                        <div class="lg:col-span-3 bg-obsidian-950/60 border-b lg:border-b-0 lg:border-r border-emerald-500/15 p-4 flex flex-col justify-between">
                            <div>
                                <div class="text-[11px] font-mono uppercase tracking-wider text-slate-400 mb-3 px-2">
                                    Engineering Modules
                                </div>
                                <div class="space-y-1.5">
                                    @foreach($consoleTabs as $tabKey => $tabData)
                                        <button
                                            type="button"
                                            onclick="window.c28SwitchConsoleTab && window.c28SwitchConsoleTab(@js($tabKey))"
                                            data-tab="{{ $tabKey }}"
                                            class="console-tab-btn w-full text-left px-3 py-2.5 rounded-lg text-xs font-medium flex items-center justify-between {{ $tabKey === $firstTabKey ? 'bg-emerald-500/15 text-neon-400 border border-emerald-500/30' : 'text-slate-400 hover:text-white hover:bg-white/5 border border-transparent' }}"
                                        >
                                            <span>{{ data_get($tabData, 'label', ucfirst($tabKey)) }}</span>
                                            <span class="font-mono text-[10px] opacity-80">{{ data_get($tabData, 'badge', 'OK') }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-6 p-3 rounded-xl bg-emerald-950/40 border border-emerald-500/20">
                                <div class="text-[11px] font-semibold text-neon-400 mb-1">Why Teams Partner With C28</div>
                                <p class="text-[11px] text-slate-300 leading-relaxed">
                                    We deliver a live, monitored, and secure application in production—not a disconnected pile of code or cloud buzzwords.
                                </p>
                            </div>
                        </div>

                        <div class="lg:col-span-9 p-5 sm:p-6 flex flex-col justify-between bg-obsidian-900/50">
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
                                <div class="p-3 rounded-xl bg-obsidian-950/80 border border-emerald-500/20">
                                    <div class="flex items-center justify-between text-[11px] text-slate-400 font-mono mb-1">
                                        <span>STEP 01</span>
                                        <span class="text-neon-400">&#10003; 1.2s</span>
                                    </div>
                                    <div class="text-xs font-bold text-white">GitHub Push Hook</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">main &rarr; GitHub Actions</div>
                                </div>
                                <div class="p-3 rounded-xl bg-obsidian-950/80 border border-emerald-500/20">
                                    <div class="flex items-center justify-between text-[11px] text-slate-400 font-mono mb-1">
                                        <span>STEP 02</span>
                                        <span class="text-neon-400">&#10003; 18.4s</span>
                                    </div>
                                    <div class="text-xs font-bold text-white">Docker Build &amp; Scan</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">0 Critical CVEs / Secrets OK</div>
                                </div>
                                <div class="p-3 rounded-xl bg-obsidian-950/80 border border-emerald-500/20">
                                    <div class="flex items-center justify-between text-[11px] text-slate-400 font-mono mb-1">
                                        <span>STEP 03</span>
                                        <span class="text-neon-400">&#10003; 4.1s</span>
                                    </div>
                                    <div class="text-xs font-bold text-white">Nginx Reverse Proxy</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">Zero-downtime swap + TLS 1.3</div>
                                </div>
                                <div class="p-3 rounded-xl bg-obsidian-950/80 border border-emerald-500/20">
                                    <div class="flex items-center justify-between text-[11px] text-slate-400 font-mono mb-1">
                                        <span>STEP 04</span>
                                        <span class="text-neon-400">LIVE</span>
                                    </div>
                                    <div class="text-xs font-bold text-white">Health &amp; Cost Monitor</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">Disk 28% • Latency 42ms</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-4 rounded-xl bg-obsidian-950/90 border border-emerald-500/20 font-mono text-xs">
                                    <div class="flex items-center justify-between text-slate-400 pb-2 mb-2 border-b border-emerald-500/10">
                                        <span>TERMINAL // DEPLOYMENT STREAM</span>
                                        <span class="text-neon-400">ssh deploy@prod-node-01</span>
                                    </div>
                                    <div id="consoleTerminalLogs" class="space-y-1.5 text-[11px] text-slate-300 leading-relaxed">
                                        @foreach((array) data_get($firstTab, 'logs', []) as $logLine)
                                            <div class="text-slate-300">{{ $logLine }}</div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="p-4 rounded-xl bg-obsidian-950/90 border border-emerald-500/20 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-xs font-semibold text-white">Customer-Focused Outcome</span>
                                            <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-emerald-500/15 text-neon-400">DevOps + DevSecOps</span>
                                        </div>
                                        <p id="consoleOutcomeDescription" class="text-xs text-slate-300 leading-relaxed mb-4">
                                            {{ data_get($firstTab, 'outcome') }}
                                        </p>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2 pt-3 border-t border-emerald-500/10 text-center">
                                        <div>
                                            <div class="text-sm font-bold text-neon-400 font-mono">0m</div>
                                            <div class="text-[10px] text-slate-400">Release Downtime</div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-white font-mono">100%</div>
                                            <div class="text-[10px] text-slate-400">Automated Checks</div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-neon-400 font-mono">-32%</div>
                                            <div class="text-[10px] text-slate-400">Cloud Waste</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 03: 4-TIER CONNECTED SERVICE ARCHITECTURE (#services) -->
    <section id="services" class="py-24 relative bg-obsidian-950/60 border-y border-emerald-500/15">
        <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-20">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="inline-block px-3.5 py-1 rounded-full text-xs font-mono uppercase tracking-widest text-neon-400 section-badge mb-4">
                    UNIFIED SERVICE ARCHITECTURE
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-4">
                    How Our Connected Engineering Studio Works
                </h2>
                <p class="text-slate-400 text-base leading-relaxed">
                    Four connected disciplines under one roof: we build the software, containerize and deploy it, integrate DevSecOps security gates into the pipeline, and maintain the production infrastructure.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-4 glass-card-highlight rounded-3xl p-8 flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl btn-emerald flex items-center justify-center text-obsidian-950 font-extrabold text-lg mb-6">
                            C28
                        </div>
                        <span class="text-xs font-mono uppercase tracking-widest text-neon-400 block mb-2">
                            DIRECT ENGINEERING PARTNER
                        </span>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-white leading-tight mb-4">
                            One Cohesive Partner From Code to Cloud
                        </h3>
                        <p class="text-sm text-slate-200 leading-relaxed mb-6">
                            Work directly with an engineering studio that bridges full-stack application development with the Linux, Docker, Nginx, and CI/CD infrastructure required to operate reliably in production.
                        </p>
                        <ul class="space-y-2.5 text-xs text-slate-200 mb-8 font-mono">
                            <li class="flex items-center gap-2"><span class="text-neon-400">&#10003;</span> Zero layers of agency account bloat</li>
                            <li class="flex items-center gap-2"><span class="text-neon-400">&#10003;</span> Full repository &amp; server ownership handoff</li>
                            <li class="flex items-center gap-2"><span class="text-neon-400">&#10003;</span> Scoped to deliverables we execute with confidence</li>
                        </ul>
                    </div>
                    <button
                        type="button"
                        onclick="window.c28OpenModal && window.c28OpenModal('Full Studio Discovery')"
                        class="btn-emerald text-obsidian-950 font-bold text-sm px-6 py-3.5 rounded-full w-full flex items-center justify-center gap-2"
                    >
                        <span>Start Your Project</span>
                        <span>&rarr;</span>
                    </button>
                </div>

                <div class="lg:col-span-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($services as $service)
                        <div class="glass-card rounded-3xl p-7 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-5">
                                    <span class="text-[11px] font-mono uppercase tracking-wider px-2.5 py-0.5 rounded-full {{ $service->tier === 'primary' ? 'bg-emerald-500/20 text-neon-400 border border-emerald-500/35' : ($service->tier === 'secondary' ? 'bg-emerald-500/10 text-emerald-300 border border-emerald-500/20' : 'bg-white/5 text-slate-300 border border-white/10') }}">
                                        {{ $service->tier_badge ?? $service->tierLabel() }}
                                    </span>
                                    <span class="text-[10px] font-mono uppercase text-slate-400">
                                        TIER: {{ strtoupper($service->tier) }}
                                    </span>
                                </div>

                                <h3 class="text-lg font-bold text-white mb-2">{{ $service->title }}</h3>
                                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed mb-4">
                                    {{ $service->short_outcome }}
                                </p>
                            </div>

                            @if(is_array($service->deliverables) && count($service->deliverables) > 0)
                                <div class="pt-4 border-t border-emerald-500/15 text-xs text-slate-300 space-y-1.5 font-mono">
                                    @foreach($service->deliverables as $deliverable)
                                        <div>&bull; {{ $deliverable }}</div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 04: PRODUCTION CODE ARTIFACT INSPECTOR -->
    <section id="capabilities" class="py-16 sm:py-24 relative">
        <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-20">
            <div class="glass-card rounded-2xl sm:rounded-3xl p-4 sm:p-8 lg:p-10 relative overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-center">
                    <div class="lg:col-span-5 min-w-0 space-y-4 sm:space-y-5">
                        <div class="inline-flex items-center gap-2 text-xs font-mono text-neon-400">
                            <span class="w-2 h-2 rounded-full bg-neon-400"></span>
                            VERIFIABLE PRODUCTION ARTIFACTS
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-bold text-white leading-snug">
                            Real Engineering Configurations, Not Marketing Fluff
                        </h2>
                        <p class="text-sm sm:text-base text-slate-300 leading-relaxed">
                            Inspect the actual multi-stage Dockerfiles, GitHub Actions CI/CD pipelines with Trivy vulnerability gates, and hardened Nginx TLS 1.3 configurations we implement for production systems.
                        </p>
                        <div class="pt-1 sm:pt-2 flex flex-wrap items-center gap-3">
                            <button
                                type="button"
                                onclick="window.c28OpenModal && window.c28OpenModal('CI/CD & Deployment Automation')"
                                class="btn-emerald text-obsidian-950 font-bold text-xs sm:text-sm px-5 py-2.5 rounded-full w-full sm:w-auto text-center justify-center"
                            >
                                Automate Your Releases
                            </button>
                        </div>
                    </div>

                    <div class="lg:col-span-7 min-w-0">
                        <div class="rounded-2xl bg-obsidian-950/95 border border-neon-400/40 shadow-[0_0_40px_-10px_rgba(16,185,129,0.3)] overflow-hidden">
                            <!-- Top Window Title & Copy Bar -->
                            <div class="px-3.5 sm:px-4 py-2.5 sm:py-3 bg-emerald-950/40 border-b border-emerald-500/20 flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500/80"></span>
                                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400/80"></span>
                                        <span class="w-2.5 h-2.5 rounded-full bg-neon-400"></span>
                                    </div>
                                    <span id="codeFileTitle" class="text-[11px] sm:text-xs font-mono text-neon-400 font-semibold truncate ml-1">{{ data_get($firstArtifact, 'title') }}</span>
                                </div>
                                <button
                                    id="codeCopyBtn"
                                    type="button"
                                    onclick="window.c28CopyCode && window.c28CopyCode(this)"
                                    data-code="{{ data_get($firstArtifact, 'code') }}"
                                    class="shrink-0 text-[11px] font-mono px-2.5 py-1 rounded-md bg-emerald-500/15 hover:bg-emerald-500/25 text-neon-400 border border-emerald-500/30 transition"
                                >
                                    Copy
                                </button>
                            </div>

                            <!-- Scrollable Artifact File Switcher Tabs -->
                            <div class="px-3 sm:px-4 py-2 bg-obsidian-900/80 border-b border-emerald-500/15 flex items-center gap-1.5 overflow-x-auto">
                                @foreach($codeArtifacts as $idx => $artifact)
                                    @php
                                        $fullTitle = (string) data_get($artifact, 'title', 'artifact');
                                        $shortTitle = basename(str_replace('\\', '/', $fullTitle));
                                    @endphp
                                    <button
                                        type="button"
                                        onclick="window.c28SelectArtifact && window.c28SelectArtifact({{ $idx }})"
                                        class="snippet-btn whitespace-nowrap shrink-0 text-[11px] font-mono px-2.5 sm:px-3 py-1.5 rounded-lg transition {{ $idx === 0 ? 'bg-emerald-500/20 text-neon-400 border border-emerald-500/30 font-semibold' : 'text-slate-400 hover:text-white border border-transparent' }}"
                                    >
                                        <span class="sm:hidden">{{ $shortTitle }}</span>
                                        <span class="hidden sm:inline">{{ $fullTitle }}</span>
                                    </button>
                                @endforeach
                            </div>

                            <pre class="p-3.5 sm:p-5 text-[11px] sm:text-xs font-mono text-slate-200 overflow-x-auto leading-relaxed max-w-full"><code id="codeBlockContent">{{ data_get($firstArtifact, 'code') }}</code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 05: INTERACTIVE 10-STEP PRODUCTION BLUEPRINT (#blueprint) -->
    @if(count($blueprintSteps) > 0)
        <section id="blueprint" class="py-24 relative bg-obsidian-950 border-t border-white/15">
            <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-20">
                <div class="text-center max-w-3xl mx-auto mb-14">
                    <span class="inline-block px-3.5 py-1 rounded-full text-xs font-mono uppercase tracking-widest text-neon-400 section-badge mb-4">
                        PROVEN DELIVERY SEQUENCE
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-4">
                        From Local Application To Hardened Production
                    </h2>
                    <p class="text-slate-400 text-base leading-relaxed">
                        Select any milestone in our 10-step engineering blueprint below to inspect how we take a web application from local source code to a monitored, automated production server.
                    </p>
                </div>

                <div class="glass-card rounded-3xl p-6 sm:p-10 lg:p-12 relative overflow-hidden border border-emerald-500/25">
                    <div class="relative z-10 w-full">
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5 mb-8">
                            @foreach($blueprintSteps as $idx => $stepItem)
                                <button
                                    type="button"
                                    onclick="window.c28SelectBlueprintStep && window.c28SelectBlueprintStep({{ $idx }})"
                                    class="blueprint-node-btn px-4 py-3.5 rounded-xl text-left font-mono text-xs transition-all border {{ $idx === $activeBpIndex ? 'btn-emerald text-obsidian-950 font-bold border-neon-400 shadow-lg' : 'bg-obsidian-950/85 text-slate-300 border-emerald-500/20 hover:border-neon-400/50 hover:text-white' }}"
                                >
                                    <div class="text-[10px] opacity-75 mb-1">STEP {{ data_get($stepItem, 'step', sprintf('%02d', $idx + 1)) }}</div>
                                    <div class="leading-snug font-semibold">{{ data_get($stepItem, 'short', data_get($stepItem, 'title')) }}</div>
                                </button>
                            @endforeach
                        </div>

                        <div class="rounded-2xl p-6 sm:p-8 bg-obsidian-900/90 border border-emerald-500/30">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 mb-6 border-b border-emerald-500/15">
                                <div>
                                    <span id="bpStepNumber" class="text-xs font-mono uppercase tracking-widest text-neon-400 block mb-1">
                                        STEP {{ data_get($activeBp, 'step', '01') }} OF {{ count($blueprintSteps) }} • {{ data_get($activeBp, 'category', 'ENGINEERING') }}
                                    </span>
                                    <h3 id="bpStepTitle" class="text-xl sm:text-2xl font-bold text-white">
                                        {{ data_get($activeBp, 'title') }}
                                    </h3>
                                </div>
                                <span id="bpStepDeliverable" class="text-xs font-mono px-3.5 py-1.5 rounded-lg bg-emerald-500/15 text-neon-400 border border-emerald-500/30 self-start md:self-center">
                                    {{ data_get($activeBp, 'deliverable') }}
                                </span>
                            </div>
                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                                <div class="lg:col-span-7 space-y-3">
                                    <p id="bpStepProblem" class="text-sm sm:text-base text-slate-300 leading-relaxed">
                                        {{ data_get($activeBp, 'problem') }}
                                    </p>
                                    <div id="bpStepOutcome" class="text-xs sm:text-sm text-neon-400 font-medium">
                                        &#10003; {{ data_get($activeBp, 'outcome') }}
                                    </div>
                                </div>
                                <div class="lg:col-span-5 bg-obsidian-950 rounded-xl p-4 border border-emerald-500/25 font-mono text-xs text-slate-300">
                                    <div class="text-slate-400 mb-1.5">// Verification Command</div>
                                    <div id="bpStepCommand" class="text-neon-400 break-all">{{ data_get($activeBp, 'cmd') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- SECTION 06: DOCUMENTED PROJECTS & LAB DEMONSTRATIONS (#projects) -->
    @if($projects->isNotEmpty())
        <section id="projects" class="py-24 relative border-t border-emerald-500/15">
            <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-20">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="inline-block px-3.5 py-1 rounded-full text-xs font-mono uppercase tracking-widest text-neon-400 section-badge mb-4">
                        TECHNICAL EVIDENCE &amp; ARCHITECTURE DOSSIERS
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-4">
                        Production Client Systems &amp; C28 Lab Demonstrations
                    </h2>
                    <p class="text-slate-400 text-base leading-relaxed">
                        Every architecture dossier explicitly distinguishes between production client delivery and transparent C28 engineering lab demonstrations.
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-7">
                    @foreach($projects as $project)
                        <article class="glass-card rounded-3xl p-7 flex flex-col justify-between border border-emerald-500/25">
                            <div>
                                <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                                    <span class="text-[11px] font-mono font-bold uppercase tracking-wider px-3 py-1 rounded-full {{ $project->isClientProduction() ? 'bg-emerald-500/20 text-neon-400 border border-emerald-500/40' : 'bg-cyan-500/15 text-cyan-300 border border-cyan-500/30' }}">
                                        {{ $project->workTypeLabel() }}
                                    </span>
                                    @if($project->service)
                                        <span class="text-[11px] font-mono text-slate-400">
                                            {{ $project->service->title }}
                                        </span>
                                    @endif
                                </div>

                                <h3 class="text-xl font-bold text-white mb-3 leading-snug">
                                    <a href="{{ route('projects.show', $project->slug) }}" class="hover:text-neon-400 transition-colors">
                                        {{ $project->title }}
                                    </a>
                                </h3>

                                <div class="space-y-3 text-xs sm:text-sm text-slate-300 mb-5">
                                    <div class="p-3.5 rounded-xl bg-obsidian-950/80 border border-emerald-500/15 leading-relaxed">
                                        <span class="font-mono text-rose-400 uppercase block mb-1 text-[11px]">Problem //</span>
                                        {{ $project->problem_summary }}
                                    </div>
                                    <div class="p-3.5 rounded-xl bg-obsidian-950/80 border border-emerald-500/20 leading-relaxed">
                                        <span class="font-mono text-neon-400 uppercase block mb-1 text-[11px]">C28 Solution //</span>
                                        {{ $project->solution_summary }}
                                    </div>
                                </div>

                                @if(is_array($project->outcome_metrics) && count($project->outcome_metrics) > 0)
                                    <div class="grid grid-cols-3 gap-2 py-3 border-y border-emerald-500/15 text-center mb-4">
                                        @foreach(array_slice($project->outcome_metrics, 0, 3) as $metric)
                                            <div>
                                                <div class="text-sm font-bold font-mono text-neon-400">{{ data_get($metric, 'value') }}</div>
                                                <div class="text-[10px] text-slate-400">{{ data_get($metric, 'label') }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if(is_array($project->tech_stack) && count($project->tech_stack) > 0)
                                    <div class="flex flex-wrap gap-1.5 mb-6">
                                        @foreach($project->tech_stack as $tech)
                                            <span class="text-[10px] font-mono px-2.5 py-0.5 rounded-full bg-obsidian-950 text-slate-300 border border-emerald-500/20">
                                                {{ $tech }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <a
                                href="{{ route('projects.show', $project->slug) }}"
                                class="inline-flex items-center justify-between w-full px-4 py-3 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 text-xs font-mono font-bold text-neon-400 transition"
                            >
                                <span>Inspect Architecture &amp; Artifacts</span>
                                <span>&rarr;</span>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- SECTION 07: TRANSPARENT ENGAGEMENT & PRICING (#engagement) -->
    @if($sprintTiers->isNotEmpty() || $retainerTiers->isNotEmpty())
        <section id="engagement" class="py-24 relative bg-obsidian-950/60 border-y border-emerald-500/15">
            <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-20">
                <div class="text-center max-w-3xl mx-auto mb-12">
                    <span class="inline-block px-3.5 py-1 rounded-full text-xs font-mono uppercase tracking-widest text-neon-400 section-badge mb-4">
                        ENGAGEMENT OPTIONS
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-4">
                        Simple, Transparent Engagements Built For Startups
                    </h2>
                    <p class="text-slate-400 text-base leading-relaxed mb-8">
                        Start with a fixed-scope deployment sprint or partner with C28 for full-cycle software development and ongoing cloud operations.
                    </p>

                    <div class="inline-flex items-center p-1.5 rounded-full bg-obsidian-950 border border-emerald-500/25">
                        <button
                            id="btnModeSprint"
                            type="button"
                            onclick="window.c28SetPricingMode && window.c28SetPricingMode('sprint')"
                            class="px-5 py-2 rounded-full text-xs font-bold transition-all btn-emerald text-obsidian-950"
                        >
                            Fixed-Scope Project Sprint
                        </button>
                        <button
                            id="btnModeRetainer"
                            type="button"
                            onclick="window.c28SetPricingMode && window.c28SetPricingMode('retainer')"
                            class="px-5 py-2 rounded-full text-xs font-semibold text-slate-400 hover:text-white transition-all"
                        >
                            Ongoing Engineering Retainer
                        </button>
                    </div>
                </div>

                <!-- Sprint Tiers Grid -->
                <div id="pricingGridSprint" class="grid grid-cols-1 lg:grid-cols-3 gap-7 items-stretch">
                    @foreach($sprintTiers as $tier)
                        <div class="{{ $tier->is_highlighted ? 'glass-card-highlight transform lg:-translate-y-2' : 'glass-card' }} rounded-3xl p-8 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-mono uppercase tracking-wider {{ $tier->is_highlighted ? 'text-neon-400' : 'text-slate-400' }}">
                                        {{ $tier->tier_badge ?? 'ENGAGEMENT TIER' }}
                                    </span>
                                    @if($tier->is_highlighted)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold uppercase bg-neon-400 text-obsidian-950">
                                            MOST POPULAR
                                        </span>
                                    @endif
                                </div>
                                <h3 class="text-xl font-bold text-white mb-2">{{ $tier->name }}</h3>
                                <p class="text-xs text-slate-300 mb-6">{{ $tier->target_audience }}</p>

                                <div class="mb-6 pb-6 border-b border-emerald-500/20">
                                    <span class="text-3xl sm:text-4xl font-extrabold text-white font-mono">{{ $tier->price_label }}</span>
                                    <span class="text-xs text-neon-400 font-mono ml-1">{{ $tier->billing_period }}</span>
                                </div>

                                <div class="text-xs font-semibold text-slate-200 uppercase tracking-wider mb-3">
                                    {{ $tier->features_heading ?? "What's Included:" }}
                                </div>
                                <ul class="space-y-3 text-xs sm:text-sm text-slate-200 mb-8">
                                    @foreach((array) $tier->features as $feature)
                                        <li class="flex items-start gap-2.5">
                                            <span class="text-neon-400 font-bold">&#10003;</span>
                                            <span>{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <button
                                type="button"
                                onclick="window.c28OpenModal && window.c28OpenModal(@js($tier->name . ' (' . $tier->price_label . ')'))"
                                class="{{ $tier->is_highlighted ? 'btn-emerald text-obsidian-950' : 'text-white bg-white/5 hover:bg-emerald-500/20 border border-emerald-500/30' }} w-full py-3.5 px-5 rounded-full text-xs sm:text-sm font-bold transition"
                            >
                                {{ $tier->cta_label }} &rarr;
                            </button>
                        </div>
                    @endforeach
                </div>

                <!-- Retainer Tiers Grid -->
                <div id="pricingGridRetainer" class="hidden grid grid-cols-1 lg:grid-cols-3 gap-7 items-stretch">
                    @foreach($retainerTiers as $tier)
                        <div class="{{ $tier->is_highlighted ? 'glass-card-highlight transform lg:-translate-y-2' : 'glass-card' }} rounded-3xl p-8 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-mono uppercase tracking-wider {{ $tier->is_highlighted ? 'text-neon-400' : 'text-slate-400' }}">
                                        {{ $tier->tier_badge ?? 'RETAINER TIER' }}
                                    </span>
                                    @if($tier->is_highlighted)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold uppercase bg-neon-400 text-obsidian-950">
                                            RECOMMENDED
                                        </span>
                                    @endif
                                </div>
                                <h3 class="text-xl font-bold text-white mb-2">{{ $tier->name }}</h3>
                                <p class="text-xs text-slate-300 mb-6">{{ $tier->target_audience }}</p>

                                <div class="mb-6 pb-6 border-b border-emerald-500/20">
                                    <span class="text-3xl sm:text-4xl font-extrabold text-white font-mono">{{ $tier->price_label }}</span>
                                    <span class="text-xs text-neon-400 font-mono ml-1">{{ $tier->billing_period }}</span>
                                </div>

                                <div class="text-xs font-semibold text-slate-200 uppercase tracking-wider mb-3">
                                    {{ $tier->features_heading ?? "What's Included:" }}
                                </div>
                                <ul class="space-y-3 text-xs sm:text-sm text-slate-200 mb-8">
                                    @foreach((array) $tier->features as $feature)
                                        <li class="flex items-start gap-2.5">
                                            <span class="text-neon-400 font-bold">&#10003;</span>
                                            <span>{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <button
                                type="button"
                                onclick="window.c28OpenModal && window.c28OpenModal(@js($tier->name . ' Retainer (' . $tier->price_label . ')'))"
                                class="{{ $tier->is_highlighted ? 'btn-emerald text-obsidian-950' : 'text-white bg-white/5 hover:bg-emerald-500/20 border border-emerald-500/30' }} w-full py-3.5 px-5 rounded-full text-xs sm:text-sm font-bold transition"
                            >
                                {{ $tier->cta_label }} &rarr;
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- SECTION 08: FOUNDER BOTTLENECK SCENARIOS (#scenarios) -->
    @if(count($scenarios) > 0)
        <section id="scenarios" class="py-24 relative">
            <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-20">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="inline-block px-3.5 py-1 rounded-full text-xs font-mono uppercase tracking-widest text-neon-400 section-badge mb-4">
                        FOUNDER BOTTLENECKS SOLVED
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-4">
                        Real Engineering Problems We Solve For Teams
                    </h2>
                    <p class="text-slate-400 text-base leading-relaxed">
                        Whether you're moving from local development to your first production server or replacing fragile manual releases, here is how C28 steps in.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($scenarios as $scenario)
                        <div class="glass-card rounded-2xl p-6 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-9 h-9 rounded-full bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-neon-400 font-mono text-xs font-bold">
                                        {{ data_get($scenario, 'code', 'S1') }}
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-white">{{ data_get($scenario, 'title') }}</div>
                                        <div class="text-[11px] text-slate-400">{{ data_get($scenario, 'subtitle') }}</div>
                                    </div>
                                </div>
                                <p class="text-xs sm:text-sm text-slate-300 italic mb-4">
                                    "{{ data_get($scenario, 'quote') }}"
                                </p>
                            </div>
                            <div class="pt-3 border-t border-emerald-500/15 text-xs text-neon-400 font-mono">
                                &rarr; C28 Solution: {{ data_get($scenario, 'solution') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- SECTION 09: ENGINEERING WALKTHROUGHS & LAB NOTES (#insights) -->
    @if($posts->isNotEmpty())
        <section id="insights" class="py-24 relative bg-obsidian-950 border-t border-white/15">
            <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-20">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="inline-block px-3.5 py-1 rounded-full text-xs font-mono uppercase tracking-widest text-neon-400 section-badge mb-4">
                        SHOW WHAT WE KNOW BY DEMONSTRATING WHAT WE DO
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-4">
                        Explore Practical Engineering Walkthroughs
                    </h2>
                    <p class="text-slate-400 text-base leading-relaxed">
                        Real deployment architectures, DevSecOps pipeline gates, and engineering lessons from our studio and lab.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
                    @foreach($posts as $post)
                        <article class="glass-card rounded-3xl overflow-hidden flex flex-col justify-between group border border-emerald-500/25">
                            <div>
                                <div class="bg-obsidian-900/90 border-b border-emerald-500/20 p-6 flex flex-col justify-between gap-4">
                                    <div class="flex flex-wrap items-center justify-between gap-2 text-[11px] font-mono">
                                        <span class="text-neon-400 font-semibold uppercase">{{ $post->categoryLabel() }}</span>
                                        <span class="px-2.5 py-0.5 rounded-full {{ $post->isClientProduction() ? 'bg-emerald-500/20 text-neon-400 border border-emerald-500/30' : 'bg-cyan-500/15 text-cyan-300 border border-cyan-500/30' }}">
                                            {{ $post->workTypeLabel() }}
                                        </span>
                                    </div>
                                    @if(filled($post->hook_text))
                                        <div class="p-3.5 rounded-xl bg-obsidian-950 border border-emerald-500/25 font-mono text-xs text-neon-400 leading-relaxed break-words">
                                            {{ $post->hook_text }}
                                        </div>
                                    @endif
                                    <div class="text-[11px] text-slate-400 font-mono">
                                        {{ $post->reading_time_minutes }} min read
                                    </div>
                                </div>

                                <div class="p-6 sm:p-7">
                                    <h3 class="text-lg sm:text-xl font-bold text-white mb-3 group-hover:text-neon-400 transition-colors leading-snug">
                                        <a href="{{ route('posts.show', $post->slug) }}">
                                            {{ $post->title }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-slate-300 leading-relaxed">
                                        {{ $post->excerpt }}
                                    </p>
                                </div>
                            </div>

                            <div class="px-6 sm:px-7 pb-6 pt-2">
                                <a href="{{ route('posts.show', $post->slug) }}" class="text-xs font-mono font-bold text-neon-400 hover:text-neon-accent flex items-center gap-1.5">
                                    <span>Read Walkthrough</span>
                                    <span>&rarr;</span>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- SECTION 10: TECHNICAL & ENGAGEMENT FAQ (#faq) -->
    @if($faqs->isNotEmpty())
        <section id="faq" class="py-24 relative bg-obsidian-900 border-t border-white/15">
            <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-20">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                    <div class="lg:col-span-5">
                        <span class="inline-block px-3.5 py-1 rounded-full text-xs font-mono uppercase tracking-widest text-neon-400 section-badge mb-4">
                            FOUNDER &amp; TECHNICAL FAQ
                        </span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-4">
                            Have Any Questions?
                        </h2>
                        <p class="text-slate-400 text-sm sm:text-base leading-relaxed mb-8">
                            Clear answers on how C28 combines software development, DevOps automation, and DevSecOps into practical engagements.
                        </p>
                        <div class="glass-card rounded-2xl p-6 border border-emerald-500/25">
                            <div class="text-sm font-bold text-white mb-1">Have a specific repository or server question?</div>
                            <p class="text-xs text-slate-400 mb-4">
                                Tell us about your current codebase or cloud setup and we'll give you an honest assessment of the best next step.
                            </p>
                            <button
                                type="button"
                                onclick="window.c28OpenModal && window.c28OpenModal('Architecture Question')"
                                class="btn-emerald text-obsidian-950 font-bold text-xs px-5 py-2.5 rounded-full"
                            >
                                Ask an Engineer &rarr;
                            </button>
                        </div>
                    </div>

                    <div class="lg:col-span-7 space-y-3">
                        @foreach($faqs as $idx => $faq)
                            <div class="glass-card rounded-2xl overflow-hidden">
                                <button
                                    type="button"
                                    onclick="window.c28ToggleFaq && window.c28ToggleFaq({{ $idx }})"
                                    class="w-full px-6 py-5 text-left flex items-center justify-between gap-4"
                                >
                                    <span class="text-sm sm:text-base font-bold text-white">{{ $faq->question }}</span>
                                    <span data-faq-icon="{{ $idx }}" class="text-neon-400 font-mono text-lg">{{ $idx === 0 ? '−' : '+' }}</span>
                                </button>
                                <div data-faq-body="{{ $idx }}" class="{{ $idx === 0 ? '' : 'hidden' }} px-6 pb-5 text-xs sm:text-sm text-slate-300 leading-relaxed border-t border-emerald-500/10 pt-4">
                                    {{ $faq->answer }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- SECTION 11: ARCHITECTURE SCOPING & CONSULTATION INTAKE (#consultation) -->
    <section id="consultation" class="py-24 relative bg-obsidian-950/85 border-t border-emerald-500/20">
        <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 2xl:px-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                <div class="lg:col-span-5">
                    <span class="inline-block px-3.5 py-1 rounded-full text-xs font-mono uppercase tracking-widest text-neon-400 section-badge mb-4">
                        ARCHITECTURE SCOPING &amp; INTAKE
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-4">
                        Scope Your Engineering Sprint Or Cloud Pipeline
                    </h2>
                    <p class="text-slate-300 text-sm sm:text-base leading-relaxed mb-8">
                        Tell us about your current repository, server environment, or product roadmap. Every inquiry goes directly to our engineering studio—no sales intermediaries.
                    </p>

                    <div class="space-y-4 mb-8">
                        <div class="glass-card rounded-2xl p-4 border border-emerald-500/20 flex items-start gap-3.5">
                            <span class="w-7 h-7 rounded-lg bg-emerald-500/15 border border-emerald-500/30 text-neon-400 font-mono text-xs font-bold flex items-center justify-center shrink-0">01</span>
                            <div>
                                <div class="text-xs font-bold text-white">Repository &amp; Infrastructure Review</div>
                                <div class="text-xs text-slate-400">We inspect your current deployment workflow, container setup, or application requirements.</div>
                            </div>
                        </div>
                        <div class="glass-card rounded-2xl p-4 border border-emerald-500/20 flex items-start gap-3.5">
                            <span class="w-7 h-7 rounded-lg bg-emerald-500/15 border border-emerald-500/30 text-neon-400 font-mono text-xs font-bold flex items-center justify-center shrink-0">02</span>
                            <div>
                                <div class="text-xs font-bold text-white">Fixed-Scope Sprint Proposal</div>
                                <div class="text-xs text-slate-400">Clear deliverables, timeline, and architecture milestones within 1 business day.</div>
                            </div>
                        </div>
                        <div class="glass-card rounded-2xl p-4 border border-emerald-500/20 flex items-start gap-3.5">
                            <span class="w-7 h-7 rounded-lg bg-emerald-500/15 border border-emerald-500/30 text-neon-400 font-mono text-xs font-bold flex items-center justify-center shrink-0">03</span>
                            <div>
                                <div class="text-xs font-bold text-white">100% Code &amp; Cloud Ownership</div>
                                <div class="text-xs text-slate-400">All Dockerfiles, CI/CD pipelines, Nginx configs, and source code remain in your accounts.</div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5 font-mono text-xs text-slate-400">
                        <div>Direct Studio Email: <a href="mailto:{{ $settings['contact.email'] ?? 'contact@c28studios.dev' }}" class="text-neon-400 hover:underline">{{ $settings['contact.email'] ?? 'contact@c28studios.dev' }}</a></div>
                        <div>Direct Studio Phone: <a href="tel:{{ preg_replace('/\s+/', '', $settings['contact.phone'] ?? '803 3880 229') }}" class="text-neon-400 hover:underline">{{ $settings['contact.phone'] ?? '803 3880 229' }}</a></div>
                        <div>Official Website: <a href="https://{{ ltrim($settings['contact.website'] ?? 'www.c28studios.dev', 'https://') }}" target="_blank" rel="noopener" class="text-neon-400 hover:underline">{{ $settings['contact.website'] ?? 'www.c28studios.dev' }}</a></div>
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <div class="glass-card rounded-3xl p-6 sm:p-10 border border-neon-400/35 shadow-2xl">
                        @if(session('inquiry_submitted'))
                            <div class="mb-6 p-5 rounded-2xl bg-emerald-500/15 border border-neon-400 text-slate-100" role="status">
                                <div class="flex items-center gap-2.5 text-neon-400 font-mono text-xs uppercase font-bold mb-1">
                                    <span>&#10003;</span>
                                    <span>INQUIRY LOGGED IN C28 PIPELINE</span>
                                </div>
                                <p class="text-xs sm:text-sm text-slate-200">{{ session('inquiry_submitted') }}</p>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="mb-6 p-4 rounded-2xl bg-rose-500/15 border border-rose-500/40 text-xs text-rose-200 space-y-1" role="alert">
                                <div class="font-mono uppercase font-bold text-rose-400 mb-1">Please check your submission:</div>
                                @foreach($errors->all() as $error)
                                    <div>&bull; {{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('inquiries.store', [], false) }}" class="space-y-4">
                            @csrf

                            <!-- Honeypot anti-spam trap -->
                            <div class="hidden" aria-hidden="true">
                                <label for="consultation_website_url">Website URL</label>
                                <input type="text" id="consultation_website_url" name="website_url" tabindex="-1" autocomplete="off" value="">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="inquiry_name" class="block text-xs font-mono uppercase text-slate-300 mb-1.5">Your Name &amp; Role *</label>
                                    <input id="inquiry_name" name="name" type="text" required value="{{ old('name') }}" placeholder="e.g. Alex Rivera — SaaS Founder" class="w-full px-4 py-2.5 rounded-xl bg-obsidian-950 border border-emerald-500/25 text-sm text-white focus:outline-none focus:border-neon-400">
                                </div>
                                <div>
                                    <label for="inquiry_email" class="block text-xs font-mono uppercase text-slate-300 mb-1.5">Work Email *</label>
                                    <input id="inquiry_email" name="email" type="email" required value="{{ old('email') }}" placeholder="founder@company.com" class="w-full px-4 py-2.5 rounded-xl bg-obsidian-950 border border-emerald-500/25 text-sm text-white focus:outline-none focus:border-neon-400">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="inquiry_company" class="block text-xs font-mono uppercase text-slate-300 mb-1.5">Company / Project Name</label>
                                    <input id="inquiry_company" name="company" type="text" value="{{ old('company') }}" placeholder="Acme Cloud Inc." class="w-full px-4 py-2.5 rounded-xl bg-obsidian-950 border border-emerald-500/25 text-sm text-white focus:outline-none focus:border-neon-400">
                                </div>
                                <div>
                                    <label for="inquiry_service_interest" class="block text-xs font-mono uppercase text-slate-300 mb-1.5">Primary Engineering Service *</label>
                                    <select id="inquiry_service_interest" name="service_interest" required class="c28-select w-full px-4 py-2.5 rounded-xl bg-obsidian-950 border border-emerald-500/25 text-sm text-white focus:outline-none focus:border-neon-400">
                                        <option value="DevOps & Cloud Engineering" data-subtitle="CI/CD, Docker, Linux &amp; Cloud Setup" @selected(old('service_interest') === 'DevOps & Cloud Engineering')>01 • DevOps &amp; Cloud Engineering</option>
                                        <option value="DevSecOps Integration" data-subtitle="Trivy Scanning, Secrets &amp; Security Gates" @selected(old('service_interest') === 'DevSecOps Integration')>02 • DevSecOps Integration</option>
                                        <option value="Software Development" data-subtitle="SaaS, Web Apps, APIs &amp; Dashboards" @selected(old('service_interest') === 'Software Development')>03 • Software Development</option>
                                        <option value="Website Development" data-subtitle="Landing Pages, Business Sites &amp; Technical SEO" @selected(old('service_interest') === 'Website Development')>04 • Website Development</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label for="inquiry_engagement_mode" class="block text-xs font-mono uppercase text-slate-300 mb-1.5">Engagement Model</label>
                                    <select id="inquiry_engagement_mode" name="engagement_mode" class="c28-select w-full px-4 py-2.5 rounded-xl bg-obsidian-950 border border-emerald-500/25 text-sm text-white focus:outline-none focus:border-neon-400">
                                        <option value="sprint_fixed" data-subtitle="Defined deliverables &amp; milestone timeline" @selected(old('engagement_mode', 'sprint_fixed') === 'sprint_fixed')>Fixed-Scope Sprint</option>
                                        <option value="monthly_retainer" data-subtitle="Ongoing DevOps, security &amp; feature velocity" @selected(old('engagement_mode') === 'monthly_retainer')>Monthly Retainer</option>
                                        <option value="architecture_review" data-subtitle="Repository, server &amp; pipeline audit" @selected(old('engagement_mode') === 'architecture_review')>Architecture Review</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="inquiry_budget_range" class="block text-xs font-mono uppercase text-slate-300 mb-1.5">Target Budget</label>
                                    <select id="inquiry_budget_range" name="budget_range" class="c28-select w-full px-4 py-2.5 rounded-xl bg-obsidian-950 border border-emerald-500/25 text-sm text-white focus:outline-none focus:border-neon-400">
                                        <option value="$1,200 – $2,500" data-subtitle="Launch / Pipeline Sprint">$1,200 – $2,500</option>
                                        <option value="$2,500 – $5,000" data-subtitle="Full Stack / Cloud Overhaul">$2,500 – $5,000</option>
                                        <option value="Monthly Retainer ($750 – $3,200/mo)" data-subtitle="Dedicated Monthly Retainer">$750 – $3,200 / mo</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="inquiry_timeline" class="block text-xs font-mono uppercase text-slate-300 mb-1.5">Target Timeline</label>
                                    <select id="inquiry_timeline" name="timeline" class="c28-select w-full px-4 py-2.5 rounded-xl bg-obsidian-950 border border-emerald-500/25 text-sm text-white focus:outline-none focus:border-neon-400">
                                        <option value="Immediate (Within 1–2 Weeks)" data-subtitle="Ready to kick off right away">Immediate (1–2 Weeks)</option>
                                        <option value="This Month" data-subtitle="Starting within 2–4 weeks">This Month</option>
                                        <option value="Planning / Next Quarter" data-subtitle="Scoping ahead for upcoming roadmap">Planning / Next Quarter</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="inquiry_message" class="block text-xs font-mono uppercase text-slate-300 mb-1.5">Repository, Infrastructure &amp; Project Context *</label>
                                <textarea id="inquiry_message" name="message" rows="4" required placeholder="Describe your current application stack, deployment pain points, or what you want to build and deploy with C28..." class="w-full px-4 py-2.5 rounded-xl bg-obsidian-950 border border-emerald-500/25 text-sm text-white focus:outline-none focus:border-neon-400">{{ old('message') }}</textarea>
                            </div>

                            <button type="submit" class="btn-emerald w-full py-3.5 rounded-full text-obsidian-950 font-bold text-sm">
                                Submit Engineering Scope Request &rarr;
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        window.__C28_STATE__ = {
            consoleTabs: @js($consoleTabs),
            blueprintSteps: @js($blueprintSteps),
            codeArtifacts: @js($codeArtifacts),
        };
    </script>
@endsection
