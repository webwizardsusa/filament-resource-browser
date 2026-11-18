<div x-on:insert="$wire.callMountedAction($event.detail)">
    @livewire($component, [
        'browserResourceType' => $browserResourceType,
        'selected' => $selected,
        'insertArguments' => $insertArguments,
        'browserOptions' => $options,
    ])
</div>
