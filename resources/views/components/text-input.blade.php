@props(['disabled' => false, 'isNotFormControl' => false])

@php
    $formControlClass = $isNotFormControl ? '' : 'form-control';
@endphp

<input @disabled($disabled) {{ $attributes->merge(['class' => $formControlClass]) }}>
