<tbody {{ $attributes->merge([
    'class' => '
        bg-white dark:bg-gray-800
        divide-y divide-gray-200 dark:divide-gray-700
        text-gray-700 dark:text-gray-200
        text-lg
    '
]) }}>
    {{ $slot }}
</tbody>
