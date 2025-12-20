<input type="{{ $type }}" placeholder="Masukkan nominal"
    {{ $attributes->merge([
        'class' => '
                            rupiah w-full rounded-r border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-700
                               text-gray-800 dark:text-gray-200
                               placeholder-gray-400 dark:placeholder-gray-400
                               p-2 text-lg
                        ',
    ]) }}
