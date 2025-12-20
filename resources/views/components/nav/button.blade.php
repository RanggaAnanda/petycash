@props([
    'href' => null,
    'active' => false,
    'icon' => null,
    'asButton' => false,
    'onclick' => null,
    'dropdown' => false,
    'arrow' => false,
    'open' => false,
])

@php
    $baseClasses = 'flex items-center px-4 py-3 rounded-lg transition-colors';
    
    if ($dropdown) {
        $baseClasses .= ' w-full justify-between';
    }
    
    $activeClasses = $active 
        ? 'text-white bg-indigo-600 dark:bg-indigo-500 shadow-sm' 
        : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700';
    
    $classes = $baseClasses . ' ' . $activeClasses;
@endphp

@if($asButton)
    <button 
        onclick="{{ $onclick }}"
        {{ $attributes->merge(['class' => $classes]) }}
    >
        <div class="flex items-center">
            @if($icon)
                {!! $icon !!}
            @endif
            <span class="font-medium">{{ $slot }}</span>
        </div>
        
        @if($dropdown && $arrow)
            <svg 
                class="w-4 h-4 transition-transform {{ $open ? 'rotate-180' : '' }}" 
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        @endif
    </button>
@else
    <a 
        href="{{ $href }}" 
        {{ $attributes->merge(['class' => $classes]) }}
    >
        @if($icon)
            {!! $icon !!}
        @endif
        <span class="font-medium">{{ $slot }}</span>
    </a>
@endif