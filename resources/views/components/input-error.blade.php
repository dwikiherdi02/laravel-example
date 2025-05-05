@props(['messages'])

@if ($messages)
    <small {{ $attributes->merge(['class' => 'form-text text-danger']) }}>{{ $messages ? $messages[0] : "" }}</small>
@endif
