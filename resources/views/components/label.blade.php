@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-100 mb-2 gaming-label']) }}>
    {{ $value ?? $slot }}
</label>
