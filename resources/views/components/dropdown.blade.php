@props([
    'name',
    'options' => [],
    'placeholder' => 'Pilih',
    'value' => null
])

<select
    name="{{ $name }}"
    {{ $attributes->merge([
        'class' => '
            w-full rounded-lg p-2 text-lg
            bg-white dark:bg-gray-700
            text-gray-800 dark:text-gray-200
            border border-gray-300 dark:border-gray-600
            focus:ring-2 focus:ring-blue-500
            focus:outline-none
        '
    ]) }}
>
    <option value="">{{ $placeholder }}</option>

    @foreach ($options as $key => $label)
        <option
            value="{{ $key }}"
            {{ old($name, $value) == $key ? 'selected' : '' }}
        >
            {{ $label }}
        </option>
    @endforeach
</select>
