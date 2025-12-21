@props([
    'label', // Nama menu, misal "Form"
    'icon', // Path SVG untuk icon
    'prefix', // Route prefix untuk active state, misal "form.*"
    'items', // Array items: [['label'=>'Uang Masuk','route'=>'form.uang-masuk'], ...]
])

<button onclick="toggleDropdown('{{ $prefix }}')"
    class="w-full flex items-center justify-between px-4 py-3 {{ Request::routeIs($prefix) ? 'text-white bg-indigo-600 dark:bg-indigo-500 shadow-sm' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }} rounded-lg transition-colors">

    <div class="flex items-center">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
        </svg>
        <span class="font-medium">{{ $label }}</span>
    </div>

    <svg id="{{ $prefix }}-arrow"
        class="w-4 h-4 transition-transform {{ Request::routeIs($prefix) ? 'rotate-180' : '' }}" fill="none"
        stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
    </svg>
</button>

<div id="{{ $prefix }}-dropdown" class="ml-8 mt-1 space-y-1 {{ Request::routeIs($prefix) ? '' : 'hidden' }}">
    @foreach ($items as $item)
        @php
            $hasRoute = isset($item['route']) && $item['route'] && Route::has($item['route']);
        @endphp
        <a @if ($hasRoute) href="{{ route($item['route']) }}"
        @else
            href="#" @endif
            class="flex items-center px-4 py-2 
            {{ $hasRoute && Request::routeIs($item['route']) ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700/50' }} 
            rounded-lg transition-colors text-sm">
            <span>{{ $item['label'] }}</span>
        </a>
    @endforeach

</div>
