@props(['messages'])

@if ($messages)
    <small {{ $attributes->merge(['class' => 'form-text text-danger']) }}>{{ $messages[0] }}</small>
@endif
