@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'bg-gray-800/50 border-gray-600 text-gray-100 placeholder-gray-400 focus:border-cyan-400 focus:ring-cyan-400 rounded-lg shadow-lg backdrop-blur-sm transition-all duration-300 hover:bg-gray-800/70 focus:bg-gray-800/80 gaming-input']) !!}>
