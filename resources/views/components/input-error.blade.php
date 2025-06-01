@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'text-sm text-red-400 font-medium mt-1 gaming-error']) }}>{{ $message }}</p>
@enderror
