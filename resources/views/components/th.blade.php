<th
    {{ $attributes->merge([
        'class' => '
            px-4 py-3 text-left font-semibold
            text-gray-700 dark:text-gray-200
            bg-gray-100 dark:bg-gray-700
            border-b border-gray-200 dark:border-gray-600
            text-lg
        '
    ]) }}
>
    {{ $slot }}
</th>
