<div class="flex items-center gap-2">
    {{ $label }}
    @if ($canCreate ?? true)
        <x-filament::button
            :size="\Filament\Support\Enums\Size::Small"
            color="gray"
            x-on:click="$wire.dispatch('show-resource-modal-add')"
        >{{ __('resource-browser::browser.add') }}</x-filament::button>
    @endif
</div>
