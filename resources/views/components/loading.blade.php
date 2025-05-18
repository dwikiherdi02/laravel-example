@props(['fullscreen' => true])

@php
    $fullscreen = $fullscreen ? 'h-100' : '';
@endphp

<div {{ $attributes->merge(['class' => 'd-flex justify-content-center align-items-center ' .  $fullscreen]) }}>
    <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>