<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-cyan-500 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:from-blue-700 hover:to-cyan-600 focus:from-blue-700 focus:to-cyan-600 active:from-blue-800 active:to-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2 focus:ring-offset-gray-900 disabled:opacity-50 transition-all duration-300 shadow-lg hover:shadow-cyan-500/25 gaming-button']) }}>
    {{ $slot }}
</button>
