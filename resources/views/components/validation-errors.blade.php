@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'bg-red-900/20 border border-red-500/30 rounded-lg p-4 mb-4 gaming-validation-errors']) }}>
        <div class="font-semibold text-red-400 mb-2">{{ __('Whoops! Something went wrong.') }}</div>

        <ul class="mt-2 list-disc list-inside text-sm text-red-300 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
