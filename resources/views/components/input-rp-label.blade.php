<input type="{{ $type }}" value="{{ $value }}" disabled
    {{ $attributes->merge([
        'class' => '
                        w-16 rounded-l border border-gray-300 dark:border-gray-600
                               bg-gray-100 dark:bg-gray-600
                               text-gray-700 dark:text-gray-200
                               p-2 text-lg text-center
                    ',
    ]) }}>
