<a href="{{ route('{{ $route }}') }}"
    {{ $attributes->merge([
        'class' => '
                flex items-center px-4 py-3 {{ Request::routeIs('{{ $route }}') ? 'text-white bg-indigo-600 dark:bg-indigo-500' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} rounded-lg transition-colors {{ Request::routeIs('{{ $route }}') ? 'shadow-sm' : '' }}
            ',
    ]) }}>
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="{{ $icon }}">
        </path>
    </svg>
    <span class="font-medium" value="{{ $value }}"></span>
</a>
