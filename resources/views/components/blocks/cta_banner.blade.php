@props(['data' => []])
@php
    $badge = data_get($data, 'badge', 'C28 — SOFTWARE & CLOUD ENGINEERING');
    $heading = data_get($data, 'heading', 'Ready to Deploy & Operate Reliably?');
    $subtext = data_get($data, 'subtext');
    $buttonLabel = data_get($data, 'button_label', 'Book a Consultation');
    $rawButtonUrl = trim((string) data_get($data, 'button_url', '#consultation'));
    $buttonUrl = (str_starts_with($rawButtonUrl, 'http://') || str_starts_with($rawButtonUrl, 'https://') || str_starts_with($rawButtonUrl, '/') || str_starts_with($rawButtonUrl, '#'))
        ? $rawButtonUrl
        : '#consultation';
@endphp

<div class="glass-card-highlight rounded-3xl p-8 sm:p-10 text-center border border-neon-400/40">
    @if(filled($badge))
        <span class="inline-block px-3.5 py-1 rounded-full text-xs font-mono uppercase tracking-widest text-neon-400 section-badge mb-4">
            {{ $badge }}
        </span>
    @endif
    <h3 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight mb-3">
        {{ $heading }}
    </h3>
    @if(filled($subtext))
        <p class="text-sm sm:text-base text-slate-300 max-w-2xl mx-auto mb-6 leading-relaxed">
            {{ $subtext }}
        </p>
    @endif
    <a href="{{ $buttonUrl }}" class="inline-flex items-center justify-center btn-emerald text-obsidian-950 font-bold text-sm px-7 py-3.5 rounded-full">
        {{ $buttonLabel }} &rarr;
    </a>
</div>

