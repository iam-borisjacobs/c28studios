@props(['data' => []])
@php
    $windowTitle = data_get($data, 'window_title', data_get($data, 'title', 'c28-control-plane / production-delivery-pipeline.yml'));
    $sshTarget = data_get($data, 'ssh_target', data_get($data, 'command', 'ssh deploy@prod-node-01'));
    $statusBadge = data_get($data, 'status_badge', 'PRODUCTION HEALTHY • 99.98% UPTIME');
    $logs = (array) data_get($data, 'logs', []);
    $outcomeSummary = data_get($data, 'outcome_summary', data_get($data, 'output'));
@endphp

<div class="glass-card rounded-2xl border border-emerald-500/30 overflow-hidden shadow-2xl">
    <div class="bg-obsidian-950/90 border-b border-emerald-500/15 px-5 py-3.5 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500/80"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-amber-400/80"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-neon-400"></span>
            </div>
            <span class="text-xs font-mono text-slate-300">{{ $windowTitle }}</span>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-[11px] font-mono text-slate-400">{{ $sshTarget }}</span>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-mono bg-emerald-500/15 text-neon-400 border border-emerald-500/30">
                <span class="w-1.5 h-1.5 rounded-full bg-neon-400"></span>
                {{ $statusBadge }}
            </span>
        </div>
    </div>

    <div class="p-5 bg-obsidian-950/95 font-mono text-xs space-y-2 text-slate-300">
        @foreach($logs as $log)
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-emerald-500/10 pb-1.5 last:border-b-0 last:pb-0">
                <div>
                    <span class="text-neon-400 mr-2">{{ data_get($log, 'timestamp', '[LOG]') }}</span>
                    <span>{{ data_get($log, 'message') }}</span>
                </div>
                @if(filled(data_get($log, 'status')))
                    <span class="text-[10px] px-2 py-0.5 rounded bg-emerald-500/15 text-neon-400 border border-emerald-500/25">
                        {{ data_get($log, 'status') }}
                    </span>
                @endif
            </div>
        @endforeach
    </div>

    @if(filled($outcomeSummary))
        <div class="px-5 py-3.5 bg-obsidian-900/90 border-t border-emerald-500/15 text-xs text-slate-300">
            <span class="font-mono text-neon-400 uppercase mr-2">Outcome //</span>
            {{ $outcomeSummary }}
        </div>
    @endif
</div>
