<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'C28 — Software & Cloud Engineering | Deploy, Secure & Operate Reliably')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-obsidian-900 bg-carbon-grid text-slate-100 antialiased selection:bg-neon-500 selection:text-obsidian-950 min-h-screen flex flex-col">
    <header class="sticky top-0 z-50 bg-obsidian-950/85 backdrop-blur-xl border-b border-emerald-500/15">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group" aria-label="C28 — Software & Cloud Engineering Home">
                <div class="w-9 h-9 rounded-lg btn-emerald flex items-center justify-center text-obsidian-950 font-extrabold text-sm tracking-tighter shadow-lg">
                    C28
                </div>
                <div class="flex flex-col">
                    <div class="flex items-center gap-2">
                        <span class="text-white font-bold text-lg tracking-tight">C28</span>
                        <span class="text-[10px] font-mono uppercase tracking-widest px-2 py-0.5 rounded bg-emerald-950/90 text-neon-400 border border-emerald-500/25">
                            Software &amp; Cloud
                        </span>
                    </div>
                    <span class="text-[11px] text-slate-400 hidden sm:inline">Engineering Studio</span>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-7 text-sm font-medium text-slate-300" aria-label="Primary Navigation">
                <a href="#services" class="hover:text-neon-400 transition-colors">Service Hierarchy</a>
                <a href="#architecture" class="hover:text-neon-400 transition-colors">Stack &amp; Architecture</a>
                <a href="{{ url('/admin') }}" class="font-mono text-xs text-neon-400 hover:text-neon-accent transition-colors">Control Plane &rarr;</a>
            </nav>
        </div>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-obsidian-950 border-t border-emerald-500/15 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
            <p>&copy; {{ date('Y') }} C28 — Software &amp; Cloud Engineering. All rights reserved.</p>
            <p class="font-mono text-neon-400">We build digital products and help teams deploy, secure, and operate them reliably.</p>
        </div>
    </footer>
</body>
</html>
