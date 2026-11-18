<div>
    @if ($adding || $editing)
        @livewire(\Webwizardsusa\ResourceBrowser\Livewire\ResourceForm::class, [
            'resourceId' => $editing,
            'browserResourceType' => $browserResourceType,
            'browserOptions' => $browserOptions,
        ])
    @elseif ($selected)
        @livewire(\Webwizardsusa\ResourceBrowser\Livewire\ResourceSelect::class, [
            'resourceId' => $selected,
            'insertArguments' => $insertArguments,
            'browserResourceType' => $browserResourceType,
            'browserOptions' => $browserOptions,
        ])
    @else
        {{ $this->table }}
    @endif
</div>
