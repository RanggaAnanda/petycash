@props(['messages' => null, 'name' => null])

@php
    // If messages not provided, try fetching from the validator errors using the provided name
    $messages = $messages ?? ($name ? $errors->get($name) : []);
@endphp

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 dark:text-red-400 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
