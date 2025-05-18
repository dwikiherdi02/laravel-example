@props(['disabled' => false, 'isNotFormControl' => false, 'value' => ''])

@php
$formControlClass = $isNotFormControl ? '' : 'form-control';
@endphp

<textarea @disabled($disabled) {{ $attributes->merge(['class' => $formControlClass]) }}>{{ $value }}</textarea>
