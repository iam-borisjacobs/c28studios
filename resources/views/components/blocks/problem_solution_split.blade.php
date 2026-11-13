@props(['data' => []])
@php
    $badge = data_get($data, 'badge', 'OUTCOME-FIRST ENGINEERING');
    $problemHeading = data_get($data, 'problem_heading', data_get($data, 'problem_title', 'Bottleneck Before C28'));
    $problem = data_get($data, 'problem');
    $solutionHeading = data_get($data, 'solution_heading', data_get($data, 'solution_title', 'C28 Engineered Solution'));
    $solution = data_get($data, 'solution');
    $verificationCmd = data_get($data, 'verification_cmd');
@endphp

<div class="glass-card rounded-2xl p-6 sm:p-8 border border-emerald-500/25">
    @if(filled($badge))
        <div class="inline-block px-3 py-1 rounded-full text-[11px] font-mono uppercase tracking-widest text-neon-400 section-badge mb-5">
            {{ $badge }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-5 rounded-xl bg-obsidian-950/90 border border-rose-500/25">
            <div class="text-xs font-mono uppercase tracking-wider text-rose-400 mb-2">
                {{ $problemHeading }}
            </div>
            <p class="text-sm text-slate-300 leading-relaxed">
                {{ $problem }}
            </p>
        </div>

        <div class="p-5 rounded-xl bg-obsidian-950/90 border border-emerald-500/35">
            <div class="text-xs font-mono uppercase tracking-wider text-neon-400 mb-2">
                {{ $solutionHeading }}
            </div>
            <p class="text-sm text-slate-200 leading-relaxed">
                {{ $solution }}
            </p>
        </div>
    </div>

    @if(filled($verificationCmd))
        <div class="mt-4 p-3 rounded-xl bg-obsidian-950 border border-emerald-500/20 font-mono text-xs text-neon-400">
            <span class="text-slate-500 mr-2">// Verification:</span>{{ $verificationCmd }}
        </div>
    @endif
</div>
