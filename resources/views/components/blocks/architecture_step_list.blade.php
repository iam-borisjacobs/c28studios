@props(['data' => []])
@php
    $heading = data_get($data, 'section_heading', 'From Local Application To Hardened Production');
    $steps = (array) data_get($data, 'steps', []);
@endphp

<div class="glass-card rounded-2xl p-6 sm:p-8 border border-emerald-500/25">
    <div class="flex items-center justify-between gap-4 pb-4 mb-6 border-b border-emerald-500/15">
        <div>
            <span class="text-[11px] font-mono uppercase tracking-widest text-neon-400 block mb-1">ARCHITECTURE SEQUENCE</span>
            <h3 class="text-xl font-bold text-white">{{ $heading }}</h3>
        </div>
        <span class="text-xs font-mono text-slate-400">{{ count($steps) }} Steps</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($steps as $stepItem)
            <div class="p-4 rounded-xl bg-obsidian-950/90 border border-emerald-500/20 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between text-[11px] font-mono text-neon-400 mb-1.5">
                        <span>STEP {{ data_get($stepItem, 'step', '01') }} • {{ data_get($stepItem, 'category', 'ENGINEERING') }}</span>
                        @if(filled(data_get($stepItem, 'deliverable')))
                            <span class="text-slate-400">{{ data_get($stepItem, 'deliverable') }}</span>
                        @endif
                    </div>
                    <h4 class="text-sm font-bold text-white mb-2">{{ data_get($stepItem, 'title') }}</h4>
                    @if(filled(data_get($stepItem, 'problem')))
                        <p class="text-xs text-slate-300 leading-relaxed mb-2">{{ data_get($stepItem, 'problem') }}</p>
                    @endif
                    @if(filled(data_get($stepItem, 'outcome')))
                        <p class="text-xs text-neon-400 font-medium mb-2">&#10003; {{ data_get($stepItem, 'outcome') }}</p>
                    @endif
                </div>
                @if(filled(data_get($stepItem, 'cmd')))
                    <div class="mt-2 pt-2 border-t border-emerald-500/10 font-mono text-[11px] text-emerald-300">
                        {{ data_get($stepItem, 'cmd') }}
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

