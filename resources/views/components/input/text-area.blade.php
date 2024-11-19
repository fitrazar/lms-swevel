@props(['disabled' => false, 'value' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'textarea textarea-bordered']) !!}>{{ $value }}</textarea>
