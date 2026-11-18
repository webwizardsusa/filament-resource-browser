<div class="fi-sc-components">
    <div id="resource-insert" class="fi-sc-form">
        {{ $this->form }}
        <div>
            <x-filament::button type="button" wire:click="insert()">{{ __('resource-browser::browser.actions.insert') }}</x-filament::button>
            @if ($canEdit ?? true)
                <x-filament::button type="button" wire:click="edit()">{{ __('resource-browser::browser.actions.edit') }}</x-filament::button>
            @endif
            <x-filament::button type="button" wire:click="back()" color="gray">{{ __('resource-browser::browser.actions.browse') }}</x-filament::button>
        </div>
    </div>
</div>
