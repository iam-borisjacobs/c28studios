@extends('layouts.app')

@php
    $brandName = $settings['brand.name'] ?? 'C28 — Software & Cloud Engineering';
    $projectMetaDesc = Str::limit(trim($project->solution_summary . ' ' . $project->problem_summary), 160);
    $projectUrl = route('projects.show', $project->slug);
    $safeUrl = static function (?string $url): ?string {
        $u = trim((string) $url);
        if ($u === '') {
            return null;
        }
        return (str_starts_with($u, 'http://') || str_starts_with($u, 'https://') || str_starts_with($u, '/')) ? $u : null;
    };
    $safeRepoUrl = $safeUrl($project->repo_url);
    $safeLiveUrl = $safeUrl($project->live_url);

    $projectSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'TechArticle',
        'headline' => $project->title,
        'description' => $project->solution_summary,
        'url' => $projectUrl,
        'author' => [
            '@type' => 'Organization',
            'name' => $brandName,
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => $brandName,
        ],
        'keywords' => is_array($project->tech_stack) ? implode(', ', $project->tech_stack) : '',
    ];

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Home',
                'item' => route('home'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'Projects & Lab',
                'item' => route('home') . '#projects',
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $project->title,
                'item' => $projectUrl,
            ],
        ],
    ];
@endphp

@section('title', $project->title . ' | ' . $brandName)
@section('meta_description', $projectMetaDesc)
@section('canonical_url', $projectUrl)
@section('og_type', 'article')

@push('structured_data')
    <script type="application/ld+json">{!! json_encode($projectSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    <script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
@endpush

@section('content')
    <section class="py-16 lg:py-24 relative">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

            <!-- 01: Breadcrumb -->
            <nav class="flex items-center gap-2 text-xs font-mono text-slate-400" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-neon-400 transition">Home</a>
                <span>/</span>
                <a href="{{ route('home') }}#projects" class="hover:text-neon-400 transition">Projects &amp; Lab</a>
                <span>/</span>
                <span class="text-neon-400 truncate">{{ $project->title }}</span>
            </nav>

            <!-- 02: Technical Header & Work Classification -->
            <div class="glass-card-highlight rounded-3xl p-8 sm:p-10 border border-neon-400/35">
                <div class="flex flex-wrap items-center gap-3 mb-5">
                    <span class="text-xs font-mono font-bold uppercase tracking-wider px-3.5 py-1 rounded-full {{ $project->isClientProduction() ? 'bg-emerald-500/25 text-neon-400 border border-emerald-500/40' : 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40' }}">
                        {{ $project->workTypeLabel() }}
                    </span>

                    @if($project->service)
                        <span class="text-xs font-mono px-3 py-1 rounded-full bg-obsidian-950/90 text-slate-300 border border-emerald-500/25">
                            Service: {{ $project->service->title }}
                        </span>
                    @endif

                    @if(filled($project->client_or_context))
                        <span class="text-xs font-mono text-slate-400">
                            Context: {{ $project->client_or_context }}
                        </span>
                    @endif
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight mb-6">
                    {{ $project->title }}
                </h1>

                @if(is_array($project->tech_stack) && count($project->tech_stack) > 0)
                    <div class="flex flex-wrap gap-2 mb-6">
                        @foreach($project->tech_stack as $tech)
                            <span class="px-3 py-1 rounded-full text-xs font-mono bg-obsidian-950/90 text-neon-400 border border-emerald-500/30">
                                #{{ $tech }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <div class="flex flex-wrap items-center gap-4 pt-4 border-t border-emerald-500/20">
                    <button
                        type="button"
                        onclick="window.c28OpenModal && window.c28OpenModal(@js('Implement: ' . $project->title))"
                        class="btn-emerald text-obsidian-950 font-bold text-xs sm:text-sm px-6 py-3 rounded-full"
                    >
                        Request Similar Architecture &rarr;
                    </button>

                    @if($safeRepoUrl)
                        <a href="{{ $safeRepoUrl }}" target="_blank" rel="noopener noreferrer" class="px-5 py-3 rounded-full text-xs font-mono text-slate-200 hover:text-neon-400 border border-emerald-500/30 bg-obsidian-950/80 transition">
                            Inspect Repository &nearr;
                        </a>
                    @endif

                    @if($safeLiveUrl)
                        <a href="{{ $safeLiveUrl }}" target="_blank" rel="noopener noreferrer" class="px-5 py-3 rounded-full text-xs font-mono text-neon-400 border border-emerald-500/30 bg-obsidian-950/80 transition">
                            Live Deployment &nearr;
                        </a>
                    @endif
                </div>
            </div>

            <!-- 03: Outcome Metrics Grid -->
            @if(is_array($project->outcome_metrics) && count($project->outcome_metrics) > 0)
                <x-blocks.metric_callout_grid :data="['heading' => 'VERIFIED ARCHITECTURE OUTCOMES', 'metrics' => $project->outcome_metrics]" />
            @endif

            <!-- 04: Problem vs. C28 Solution Specification -->
            <x-blocks.problem_solution_split :data="[
                'badge' => 'ENGINEERING SPECIFICATION',
                'problem_heading' => 'Operational Bottleneck',
                'problem' => $project->problem_summary,
                'solution_heading' => 'C28 Engineered Architecture',
                'solution' => $project->solution_summary,
            ]" />

            <!-- 05: Dynamic Visual Builder Blocks -->
            @if(is_array($project->builder_blocks) && count($project->builder_blocks) > 0)
                <div class="space-y-6">
                    <div class="text-xs font-mono uppercase tracking-widest text-neon-400">
                        // DYNAMIC ARCHITECTURE BLOCKS
                    </div>
                    <x-blocks.renderer :blocks="$project->builder_blocks" />
                </div>
            @endif

            <!-- 06: Production Code Artifacts -->
            @if(is_array($project->code_artifacts) && count($project->code_artifacts) > 0)
                <div class="space-y-6">
                    <div class="text-xs font-mono uppercase tracking-widest text-neon-400">
                        // VERIFIABLE CONFIGURATION ARTIFACTS
                    </div>
                    @foreach($project->code_artifacts as $artifact)
                        <x-blocks.code_artifact_viewer :data="$artifact" />
                    @endforeach
                </div>
            @endif

            <!-- 07: Project Architecture Steps -->
            @if(is_array($project->architecture_steps) && count($project->architecture_steps) > 0)
                <x-blocks.architecture_step_list :data="[
                    'section_heading' => 'Execution & Delivery Sequence',
                    'steps' => $project->architecture_steps,
                ]" />
            @endif

            <!-- 08: Related Projects -->
            @if($relatedProjects->isNotEmpty())
                <div class="pt-10 border-t border-emerald-500/15">
                    <h2 class="text-xl font-bold text-white mb-6">Related Engineering Demonstrations &amp; Case Studies</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($relatedProjects as $related)
                            <a href="{{ route('projects.show', $related->slug) }}" class="glass-card rounded-2xl p-6 block hover:border-neon-400/50 transition">
                                <span class="text-[10px] font-mono uppercase px-2.5 py-0.5 rounded-full {{ $related->isClientProduction() ? 'bg-emerald-500/20 text-neon-400' : 'bg-cyan-500/15 text-cyan-300' }}">
                                    {{ $related->workTypeLabel() }}
                                </span>
                                <h3 class="text-base font-bold text-white mt-3 mb-2">{{ $related->title }}</h3>
                                <p class="text-xs text-slate-400">{{ Str::limit($related->solution_summary, 110) }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

