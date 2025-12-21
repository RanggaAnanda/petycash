@props([
    'type' => 'text',
    'name' => null,
    'value' => null,
    'readonly' => false,
    'placeholder' => ''
])

@php
    // normalize name: use provided name, fallback to attributes 'name' or 'id'
    $name = $name ?? ($attributes->get('name') ?? $attributes->get('id') ?? null);

    // Avoid passing null to old() which returns the whole old input array (causes htmlspecialchars error)
    $rawOld = $name ? old($name, $value) : $value;

    // Ensure value is a scalar string for the value attribute
    if (is_array($rawOld) || is_object($rawOld)) {
        // prefer the provided default scalar value, otherwise empty string
        $inputValue = is_scalar($value) ? $value : '';
    } else {
        $inputValue = $rawOld;
    }

    $hasError = $name && isset($errors) && $errors->has($name);
@endphp

<input
    type="{{ $type }}"
    @if($name) name="{{ $name }}" @endif
    value="{{ $inputValue }}"
    @if($readonly) readonly @endif

    {{ $attributes->merge([
        'class' => '
            w-full rounded-lg p-2
            bg-white dark:bg-gray-700
            text-gray-800 dark:text-gray-200
            placeholder-gray-400
            border ' . ($hasError
                ? 'border-red-500 focus:ring-red-500'
                : 'border-gray-300 dark:border-gray-600 focus:ring-blue-500'
            ) . '
            focus:ring-2 focus:outline-none
        '
    ]) }}
>

@if($hasError)
    <p class="mt-1 text-sm text-red-600 dark:text-red-400">
        {{ $errors->first($name) }}
    </p>
@endif
