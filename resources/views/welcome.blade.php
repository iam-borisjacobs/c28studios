@extends('layouts.app')

@section('content')
<section class="relative pt-16 pb-24 overflow-hidden hero-beam">
    <div class="pointer-events-none absolute top-0 right-[14%] w-36 h-[480px] vertical-light-pillar opacity-80 hidden sm:block"></div>
    <div class="pointer-events-none absolute top-0 right-[18%] w-1.5 h-[380px] vertical-light-core opacity-75 hidden sm:block"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full section-badge mb-6">
                <span class="w-2 h-2 rounded-full bg-neon-400 animate-ping"></span>
                <span class="text-xs font-mono uppercase tracking-widest text-neon-400 font-semibold">
                    C28 — SOFTWARE &amp; CLOUD ENGINEERING
                </span>
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-[1.1] mb-6">
                We build digital products and help teams deploy, secure, and operate them reliably.
            </h1>

            <p class="text-base sm:text-lg text-slate-300 leading-relaxed mb-10 max-w-2xl">
                Connected full-stack product development, Docker containerization, automated GitHub Actions CI/CD pipelines, DevSecOps security gates, and Linux/Nginx production operations under one roof.
            </p>
        </div>

        <div id="services" class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- 1. Primary: DevOps & Cloud Engineering -->
            <div class="glass-card-highlight rounded-2xl p-6 flex flex-col justify-between">
                <div>
                    <span class="inline-block text-[11px] font-mono uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-neon-400 border border-emerald-500/30 mb-4">
                        01 • Primary Service
                    </span>
                    <h2 class="text-lg font-bold text-white mb-2">DevOps &amp; Cloud Engineering</h2>
                    <p class="text-xs text-slate-300 leading-relaxed mb-4">
                        Application deployment, Linux server configuration, CI/CD pipelines, Docker containerization, cloud setup, and operational monitoring.
                    </p>
                </div>
                <div class="pt-3 border-t border-emerald-500/20 font-mono text-[11px] text-neon-400">
                    docker • github-actions • nginx • linux
                </div>
            </div>

            <!-- 2. Secondary: DevSecOps -->
            <div class="glass-card rounded-2xl p-6 flex flex-col justify-between">
                <div>
                    <span class="inline-block text-[11px] font-mono uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 mb-4">
                        02 • Secondary Service
                    </span>
                    <h2 class="text-lg font-bold text-white mb-2">DevSecOps</h2>
                    <p class="text-xs text-slate-300 leading-relaxed mb-4">
                        Dependency vulnerability scanning, secret management, container image scanning, and automated CI/CD security checks.
                    </p>
                </div>
                <div class="pt-3 border-t border-emerald-500/15 font-mono text-[11px] text-slate-400">
                    trivy • secret-vaults • tls-1.3
                </div>
            </div>

            <!-- 3. Supporting: Software Development -->
            <div class="glass-card rounded-2xl p-6 flex flex-col justify-between">
                <div>
                    <span class="inline-block text-[11px] font-mono uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-white/5 text-slate-300 border border-white/10 mb-4">
                        03 • Supporting Service
                    </span>
                    <h2 class="text-lg font-bold text-white mb-2">Software Development</h2>
                    <p class="text-xs text-slate-300 leading-relaxed mb-4">
                        SaaS applications, APIs, backend systems, business web applications, and custom internal dashboards engineered for production.
                    </p>
                </div>
                <div class="pt-3 border-t border-emerald-500/15 font-mono text-[11px] text-slate-400">
                    laravel • apis • dashboards • mysql
                </div>
            </div>

            <!-- 4. Supporting: Website Development -->
            <div class="glass-card rounded-2xl p-6 flex flex-col justify-between">
                <div>
                    <span class="inline-block text-[11px] font-mono uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-white/5 text-slate-300 border border-white/10 mb-4">
                        04 • Supporting Service
                    </span>
                    <h2 class="text-lg font-bold text-white mb-2">Website Development</h2>
                    <p class="text-xs text-slate-300 leading-relaxed mb-4">
                        High-craft business websites, landing pages, e-commerce builds, redesigns, performance tuning, and technical SEO.
                    </p>
                </div>
                <div class="pt-3 border-t border-emerald-500/15 font-mono text-[11px] text-slate-400">
                    blade • alpine • tailwind • seo
                </div>
            </div>
        </div>

        <div id="architecture" class="mt-10 glass-card rounded-2xl p-6 border border-emerald-500/25 font-mono text-xs">
            <div class="flex flex-wrap items-center justify-between gap-2 pb-3 mb-3 border-b border-emerald-500/15 text-slate-400">
                <span>C28 FOUNDATION TELEMETRY // PHASE 01</span>
                <span class="text-neon-400">STATUS: ONLINE</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-slate-300">
                <div><span class="text-neon-400">[STACK]</span> Laravel 11 + MySQL + Filament v3</div>
                <div><span class="text-neon-400">[ASSETS]</span> Vite + Tailwind v3.4 + Alpine.js</div>
                <div><span class="text-neon-400">[CONTROL PLANE]</span> /admin (FilamentUser RBAC)</div>
            </div>
        </div>
    </div>
</section>
@endsection
