@props(['disabled' => false, 'id' => null, 'name' => null])

<select
    @if ($id != null) id="{{ $id }}" @endif
    @if ($name != null) name="{{ $name }}" @endif
    {{ $attributes->merge(['class' => 'form-control']) }}
    @disabled($disabled)
    >
    {{ $slot ?? '' }}
</select>
