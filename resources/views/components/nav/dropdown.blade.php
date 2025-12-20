@props([
    'id' => null,
    'open' => false,
])

<div>
    {{ $trigger }}
    
    <div 
        id="{{ $id }}-dropdown" 
        class="ml-8 mt-1 space-y-1 {{ $open ? '' : 'hidden' }}"
    >
        {{ $slot }}
    </div>
</div>