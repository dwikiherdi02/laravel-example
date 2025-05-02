@props(['value', 'isRequired' => false])

<label {{ $attributes->merge([]) }}>{{ $value ?? $slot }} @if ($isRequired)<small class="text-danger">*</small>@endif</label>
