<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-6 py-3 bg-gray-700/50 border border-gray-600 rounded-lg font-semibold text-sm text-gray-200 uppercase tracking-wider shadow-lg hover:bg-gray-600/60 hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:ring-offset-gray-900 disabled:opacity-50 transition-all duration-300 backdrop-blur-sm gaming-secondary-button']) }}>
    {{ $slot }}
</button>
