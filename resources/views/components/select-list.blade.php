@props(['disabled' => false, 'id' => null])

<select   
    @if ($id != null) id="{{ $id }}" @endif 
    {{ $attributes->merge(['class' => 'form-control']) }}
    @disabled($disabled)
    >
    {{ $slot ?? '' }}
</select>