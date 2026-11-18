<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{ state: $wire.$entangle(@js($getStatePath())) }" {{ $getExtraAttributeBag() }}>
        <div>
            <input {{
                $attributes
                    ->merge([
                        'id' => $getId(),
                        'type' => 'hidden',
                        $applyStateBindingModifiers('wire:model') => $getStatePath(),
                    ], escape: false)
                    ->merge($getExtraAttributes(), escape: false)
                    ->class(['fi-fo-hidden'])
            }} />
            {{ $getAction('open-browser') }}
        </div>

        @if ($getResource())
            <div class="mt-4">{{ $getResourcePreview() }}</div>
        @endif
    </div>
</x-dynamic-component>
