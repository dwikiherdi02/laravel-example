@props([
    'wireModel',
    'name',
    'id',
    'label',
    'messages',
    'isWithoutLabel' => false,
    'disabled' => false
])

<div class="form-group">
    @if (!$isWithoutLabel)
        <label for="username">{{ $label }}</label>
    @endif
    <input type="text" wire:model="{{ $wireModel }}" name="{{ $name }}" id="{{ $id }}" {{ $attributes->merge(['class' => 'form-control']) }} placeholder="{{ $label }}"
        aria-describedby="{{ $id . 'Help' }}">
    @if ($messages)
        <small id="{{ $id . 'Help' }}" class="form-text text-danger">{{ $messages[0] }}</small>
    @endif
</div>
