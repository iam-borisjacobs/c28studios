@props(['data' => []])
@php
    $title = data_get($data, 'title', data_get($data, 'filename', 'production-artifact.yml'));
    $language = strtoupper((string) data_get($data, 'language', 'YAML'));
    $code = (string) data_get($data, 'code', '');
    $caption = data_get($data, 'caption');
@endphp

<div class="rounded-2xl bg-obsidian-950/95 border border-neon-400/35 shadow-[0_0_40px_-10px_rgba(16,185,129,0.25)] overflow-hidden">
    <div class="px-4 py-3 bg-emerald-950/40 border-b border-emerald-500/20 flex items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-neon-400"></span>
            <span class="text-xs font-mono text-neon-400 font-semibold">{{ $title }}</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-[10px] font-mono uppercase px-2 py-0.5 rounded bg-emerald-500/15 text-slate-300 border border-emerald-500/25">
                {{ $language }}
            </span>
            <button
                type="button"
                onclick="window.c28CopyCode && window.c28CopyCode(this)"
                data-code="{{ $code }}"
                class="text-[11px] font-mono px-2.5 py-1 rounded bg-white/5 hover:bg-emerald-500/20 text-slate-300 hover:text-neon-400 border border-emerald-500/20 transition"
            >
                Copy
            </button>
        </div>
    </div>

    <pre class="p-5 text-xs font-mono text-slate-200 overflow-x-auto leading-relaxed"><code>{{ $code }}</code></pre>

    @if(filled($caption))
        <div class="px-4 py-2.5 bg-obsidian-900/80 border-t border-emerald-500/15 text-[11px] font-mono text-slate-400">
            {{ $caption }}
        </div>
    @endif
</div>

