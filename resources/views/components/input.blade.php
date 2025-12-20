@props([
    'type' => 'text',
    'name' => null,
    'value' => null,
    'readonly' => false,
    'placeholder' => ''
])
<input
    type="{{ $type }}"
    value="{{ old($name, $value) }}"
    readonly="{{ $readonly ? 'readonly' : '' }}"

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
