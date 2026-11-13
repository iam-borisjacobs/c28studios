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
    <!-- SECTION 02: ENGINEERING HERO & LIVE CONTROL PLANE CONSOLE -->
    <section class="relative pt-14 pb-24 lg:pt-20 lg:pb-28 overflow-hidden hero-beam">
        <div class="pointer-events-none absolute top-0 right-[12%] lg:right-[18%] w-36 h-[520px] vertical-light-pillar opacity-80 hidden sm:block"></div>
        <div class="pointer-events-none absolute top-0 right-[16.5%] lg:right-[21.5%] w-1.5 h-[420px] vertical-light-core opacity-75 hidden sm:block"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full section-badge mb-6">
                    <span class="w-2 h-2 rounded-full bg-neon-400 animate-ping"></span>
                    <span class="text-xs font-mono uppercase tracking-widest text-neon-400 font-semibold">
                        {{ $heroBadge }}
                    </span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-[1.1] mb-4">
                    {{ $heroHeadline }}
                </h1>

                <p class="text-sm font-mono text-neon-400 mb-4">
                    {{ $brandStatement }}
                </p>

                <p class="text-base sm:text-lg text-slate-300/90 leading-relaxed mb-8 max-w-2xl">
                    {{ $heroSubheadline }}
                </p>

                <div class="flex flex-wrap items-center gap-4 mb-8">
                    <button
                        type="button"
                        onclick="window.c28OpenModal && window.c28OpenModal('Architecture Review & Scope')"
                        class="btn-emerald text-obsidian-950 font-bold text-sm px-7 py-3.5 rounded-full flex items-center gap-2"
                    >
                        <span>{{ $settings['cta.primary_label'] ?? 'Book a Consultation' }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </button>
                    <a href="#services" class="px-6 py-3.5 rounded-full text-xs sm:text-sm font-mono text-slate-200 hover:text-neon-400 border border-emerald-500/30 bg-obsidian-950/70 transition">
                        Explore 4-Tier Architecture &rarr;
                    </a>
                </div>

                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-xs text-slate-400 font-mono">
                    <span class="flex items-center gap-1.5">
                        <span class="text-neon-400">&#10003;</span> Automated GitHub &rarr; Production CI/CD
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="text-neon-400">&#10003;</span> Docker, Linux &amp; Nginx TLS 1.3 Hardening
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="text-neon-400">&#10003;</span> DevSecOps Scanning &amp; Full-Stack Software
                    </span>
                </div>
            </div>

            <!-- Interactive Control Plane Console -->
            <div class="mt-12 relative">
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
                        <p class="text-sm text-slate-200/90 leading-relaxed mb-6">
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
    <section id="capabilities" class="py-24 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-card rounded-3xl p-6 sm:p-10 relative overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-5 space-y-5">
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
                        <div class="pt-2 flex flex-wrap items-center gap-3">
                            <button
                                type="button"
                                onclick="window.c28OpenModal && window.c28OpenModal('CI/CD & Deployment Automation')"
                                class="btn-emerald text-obsidian-950 font-bold text-xs sm:text-sm px-5 py-2.5 rounded-full"
                            >
                                Automate Your Releases
                            </button>
                        </div>
                    </div>

                    <div class="lg:col-span-7">
                        <div class="rounded-2xl bg-obsidian-950/95 border border-neon-400/40 shadow-[0_0_40px_-10px_rgba(16,185,129,0.3)] overflow-hidden">
                            <div class="px-4 py-3 bg-emerald-950/40 border-b border-emerald-500/20 flex flex-wrap items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-neon-400"></span>
                                    <span id="codeFileTitle" class="text-xs font-mono text-neon-400 font-semibold">{{ data_get($firstArtifact, 'title') }}</span>
                                </div>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($codeArtifacts as $idx => $artifact)
                                        <button
                                            type="button"
                                            onclick="window.c28SelectArtifact && window.c28SelectArtifact({{ $idx }})"
                                            class="snippet-btn text-[11px] font-mono px-2.5 py-1 rounded {{ $idx === 0 ? 'bg-emerald-500/20 text-neon-400 border border-emerald-500/30' : 'text-slate-400 hover:text-white' }}"
                                        >
                                            {{ data_get($artifact, 'title') }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            <pre class="p-5 text-xs font-mono text-slate-200 overflow-x-auto leading-relaxed"><code id="codeBlockContent">{{ data_get($firstArtifact, 'code') }}</code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 05: INTERACTIVE 10-STEP PRODUCTION BLUEPRINT (#blueprint) -->
    @if(count($blueprintSteps) > 0)
        <section id="blueprint" class="py-24 relative bg-obsidian-950/50 border-t border-emerald-500/15">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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

                <div class="rounded-3xl p-6 sm:p-10 relative overflow-hidden border border-neon-400/35 bg-gradient-to-b from-emerald-950/40 via-obsidian-900/95 to-obsidian-950">
                    <div class="relative z-10 max-w-5xl mx-auto">
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-8">
                            @foreach($blueprintSteps as $idx => $stepItem)
                                <button
                                    type="button"
                                    onclick="window.c28SelectBlueprintStep && window.c28SelectBlueprintStep({{ $idx }})"
                                    class="blueprint-node-btn px-3 py-2.5 rounded-xl text-left font-mono text-xs transition-all border {{ $idx === $activeBpIndex ? 'btn-emerald text-obsidian-950 font-bold border-neon-400 shadow-lg' : 'bg-obsidian-950/85 text-slate-300 border-emerald-500/20 hover:border-neon-400/50 hover:text-white' }}"
                                >
                                    <div class="text-[10px] opacity-75">STEP {{ data_get($stepItem, 'step', sprintf('%02d', $idx + 1)) }}</div>
                                    <div class="truncate">{{ data_get($stepItem, 'short', data_get($stepItem, 'title')) }}</div>
                                </button>
                            @endforeach
                        </div>

                        <div class="glass-card rounded-2xl p-6 border border-emerald-500/30">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 mb-4 border-b border-emerald-500/15">
                                <div>
                                    <span id="bpStepNumber" class="text-xs font-mono uppercase tracking-widest text-neon-400">
                                        STEP {{ data_get($activeBp, 'step', '01') }} OF {{ count($blueprintSteps) }} • {{ data_get($activeBp, 'category', 'ENGINEERING') }}
                                    </span>
                                    <h3 id="bpStepTitle" class="text-xl font-bold text-white mt-0.5">
                                        {{ data_get($activeBp, 'title') }}
                                    </h3>
                                </div>
                                <span id="bpStepDeliverable" class="text-xs font-mono px-3 py-1.5 rounded-lg bg-emerald-500/15 text-neon-400 border border-emerald-500/30 self-start md:self-center">
                                    {{ data_get($activeBp, 'deliverable') }}
                                </span>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                                <div class="md:col-span-7">
                                    <p id="bpStepProblem" class="text-sm text-slate-300 leading-relaxed mb-3">
                                        {{ data_get($activeBp, 'problem') }}
                                    </p>
                                    <div id="bpStepOutcome" class="text-xs text-neon-400 font-medium">
                                        &#10003; {{ data_get($activeBp, 'outcome') }}
                                    </div>
                                </div>
                                <div class="md:col-span-5 bg-obsidian-950/90 rounded-xl p-3.5 border border-emerald-500/20 font-mono text-[11px] text-slate-300">
                                    <div class="text-slate-500 mb-1">// Verification Command</div>
                                    <div id="bpStepCommand" class="text-neon-400">{{ data_get($activeBp, 'cmd') }}</div>
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
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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

                                <div class="space-y-2.5 text-xs text-slate-300 mb-5">
                                    <div class="p-3 rounded-xl bg-obsidian-950/80 border border-emerald-500/15">
                                        <span class="font-mono text-rose-400 uppercase block mb-0.5">Problem //</span>
                                        {{ Str::limit($project->problem_summary, 130) }}
                                    </div>
                                    <div class="p-3 rounded-xl bg-obsidian-950/80 border border-emerald-500/20">
                                        <span class="font-mono text-neon-400 uppercase block mb-0.5">C28 Solution //</span>
                                        {{ Str::limit($project->solution_summary, 130) }}
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
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
        <section id="insights" class="py-24 relative bg-obsidian-950/50 border-t border-emerald-500/15">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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

                <div class="grid grid-cols-1 md:grid-cols-3 gap-7">
                    @foreach($posts as $post)
                        <article class="glass-card rounded-3xl overflow-hidden flex flex-col justify-between group border border-emerald-500/20">
                            <div>
                                <div class="h-44 bg-gradient-to-br from-emerald-950 via-obsidian-900 to-obsidian-950 border-b border-emerald-500/20 p-5 flex flex-col justify-between">
                                    <div class="flex items-center justify-between gap-2 text-[10px] font-mono">
                                        <span class="text-neon-400 uppercase">{{ $post->categoryLabel() }}</span>
                                        <span class="px-2 py-0.5 rounded {{ $post->isClientProduction() ? 'bg-emerald-500/20 text-neon-400' : 'bg-cyan-500/15 text-cyan-300' }}">
                                            {{ $post->workTypeLabel() }}
                                        </span>
                                    </div>
                                    @if(filled($post->hook_text))
                                        <div class="my-auto p-3 rounded-xl bg-obsidian-950/90 border border-emerald-500/25 font-mono text-[11px] text-emerald-300 truncate">
                                            {{ $post->hook_text }}
                                        </div>
                                    @endif
                                    <div class="text-[11px] text-slate-400 font-mono">
                                        {{ $post->reading_time_minutes }} min read
                                    </div>
                                </div>

                                <div class="p-6">
                                    <h3 class="text-lg font-bold text-white mb-2 group-hover:text-neon-400 transition-colors">
                                        <a href="{{ route('posts.show', $post->slug) }}">
                                            {{ $post->title }}
                                        </a>
                                    </h3>
                                    <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                                        {{ $post->excerpt }}
                                    </p>
                                </div>
                            </div>

                            <div class="px-6 pb-6">
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
        <section id="faq" class="py-24 relative bg-obsidian-900/40 border-t border-emerald-500/15">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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

    <script>
        window.__C28_STATE__ = {
            consoleTabs: @js($consoleTabs),
            blueprintSteps: @js($blueprintSteps),
            codeArtifacts: @js($codeArtifacts),
        };
    </script>
@endsection
