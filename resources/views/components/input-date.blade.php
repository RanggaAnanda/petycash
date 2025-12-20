@props(['name', 'value' => now()->format('Y-m-d'), 'readonly' => false])

<input type="date" name="{{ $name }}" value="{{ old($name, $value) }}" {{ $readonly ? 'readonly' : '' }}
    {{ $attributes->merge([
        'class' => '
                w-full rounded-lg p-2 text-lg
                bg-white dark:bg-gray-700
                text-gray-800 dark:text-gray-200
                border border-gray-300 dark:border-gray-600
                focus:ring-2 focus:ring-blue-500
                focus:outline-none
            ',
    ]) }}>
