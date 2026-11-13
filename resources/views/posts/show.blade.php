@extends('layouts.app')

@section('title', $post->title . ' | ' . ($settings['brand.name'] ?? 'C28 — Software & Cloud Engineering'))

@section('content')
    <article class="py-16 lg:py-24 relative">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            <!-- 01: Breadcrumb -->
            <nav class="flex items-center gap-2 text-xs font-mono text-slate-400" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-neon-400 transition">Home</a>
                <span>/</span>
                <a href="{{ route('home') }}#insights" class="hover:text-neon-400 transition">Engineering Insights</a>
                <span>/</span>
                <span class="text-neon-400 truncate">{{ $post->title }}</span>
            </nav>

            <!-- 02: Article Header -->
            <header class="glass-card-highlight rounded-3xl p-8 sm:p-10 border border-neon-400/35">
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <span class="text-xs font-mono uppercase tracking-wider px-3 py-1 rounded-full bg-emerald-500/20 text-neon-400 border border-emerald-500/35">
                        {{ $post->categoryLabel() }}
                    </span>

                    <span class="text-xs font-mono uppercase tracking-wider px-3 py-1 rounded-full {{ $post->isClientProduction() ? 'bg-emerald-500/25 text-neon-400 border border-emerald-500/40' : 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40' }}">
                        {{ $post->workTypeLabel() }}
                    </span>

                    <span class="text-xs font-mono text-slate-400">
                        {{ $post->reading_time_minutes }} min read
                    </span>

                    @if($post->published_at)
                        <span class="text-xs font-mono text-slate-400">
                            • {{ $post->published_at->format('M d, Y') }}
                        </span>
                    @endif
                </div>

                <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight mb-4">
                    {{ $post->title }}
                </h1>

                <p class="text-base text-slate-300 leading-relaxed mb-6">
                    {{ $post->excerpt }}
                </p>

                @if(filled($post->hook_text))
                    <div class="p-4 rounded-xl bg-obsidian-950/95 border border-emerald-500/30 font-mono text-xs text-neon-400">
                        <span class="text-slate-500 mr-2">// TELEMETRY HOOK:</span>{{ $post->hook_text }}
                    </div>
                @endif
            </header>

            <!-- 03: Dynamic Article Content Blocks -->
            @if(is_array($post->content_blocks) && count($post->content_blocks) > 0)
                <x-blocks.renderer :blocks="$post->content_blocks" />
            @endif

            <!-- 04: Associated Code Snippets -->
            @if(is_array($post->code_snippets) && count($post->code_snippets) > 0)
                <div class="space-y-6">
                    <div class="text-xs font-mono uppercase tracking-widest text-neon-400">
                        // CONFIGURATION &amp; TERMINAL SNIPPETS
                    </div>
                    @foreach($post->code_snippets as $snippet)
                        <x-blocks.code_artifact_viewer :data="$snippet" />
                    @endforeach
                </div>
            @endif

            <!-- 05: Implementation CTA -->
            <x-blocks.cta_banner :data="[
                'badge' => 'C28 — SOFTWARE & CLOUD ENGINEERING',
                'heading' => 'Want This Architecture Implemented For Your Repository?',
                'subtext' => 'Partner with C28 to containerize your application, automate GitHub Actions CI/CD, and harden your production Linux & Nginx infrastructure.',
                'button_label' => 'Book Architecture Consultation',
                'button_url' => route('home') . '#engagement',
            ]" />

            <!-- 06: Related Walkthroughs -->
            @if($relatedPosts->isNotEmpty())
                <div class="pt-8 border-t border-emerald-500/15">
                    <h2 class="text-xl font-bold text-white mb-6">More Engineering Walkthroughs</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($relatedPosts as $related)
                            <a href="{{ route('posts.show', $related->slug) }}" class="glass-card rounded-2xl p-6 block hover:border-neon-400/50 transition">
                                <div class="flex items-center justify-between text-[10px] font-mono text-neon-400 mb-2">
                                    <span>{{ $related->categoryLabel() }}</span>
                                    <span>{{ $related->reading_time_minutes }} min read</span>
                                </div>
                                <h3 class="text-base font-bold text-white mb-2">{{ $related->title }}</h3>
                                <p class="text-xs text-slate-400">{{ Str::limit($related->excerpt, 110) }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </article>
@endsection

