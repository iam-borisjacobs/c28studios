@props(['data' => []])
@php
    $heading = data_get($data, 'heading', 'Verified Engineering Telemetry');
    $metrics = (array) data_get($data, 'metrics', []);
@endphp

<div class="glass-card rounded-2xl p-6 border border-emerald-500/25">
    @if(filled($heading))
        <div class="text-xs font-mono uppercase tracking-widest text-neon-400 mb-4">
            {{ $heading }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @foreach($metrics as $metric)
            <div class="p-4 rounded-xl bg-obsidian-950/90 border border-emerald-500/20">
                <div class="text-2xl font-extrabold font-mono text-neon-400">
                    {{ data_get($metric, 'value') }}
                </div>
                <div class="text-xs font-bold text-white mt-1">
                    {{ data_get($metric, 'label') }}
                </div>
                @if(filled(data_get($metric, 'note')))
                    <div class="text-[11px] text-slate-400 mt-1">
                        {{ data_get($metric, 'note') }}
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

