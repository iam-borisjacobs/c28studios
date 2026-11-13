@props(['data' => []])
@php
    $heading = data_get($data, 'heading', data_get($data, 'section_heading'));
    $body = (string) data_get($data, 'body', '');
    $paragraphs = array_filter(preg_split('/\R{2,}/', trim($body)) ?: []);
@endphp

<div class="glass-card rounded-2xl p-6 sm:p-8 border border-emerald-500/20 space-y-3">
    @if(filled($heading))
        <h3 class="text-xl font-bold text-white tracking-tight">
            {{ $heading }}
        </h3>
    @endif

    <div class="space-y-3 text-sm sm:text-base text-slate-300 leading-relaxed">
        @foreach($paragraphs as $paragraph)
            <p>{{ $paragraph }}</p>
        @endforeach
    </div>
</div>
