<?php

namespace Webwizardsusa\ResourceBrowser\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Webwizardsusa\ResourceBrowser\Concerns\ComponentInteractsWithBrowser;

/**
 * Shows the BrowsableResource's select form for the picked record, and inserts it with the form's state.
 *
 * @property Schema $form
 */
class ResourceSelect extends Component implements HasActions, HasSchemas
{
    use ComponentInteractsWithBrowser;
    use EvaluatesClosures;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public mixed $resourceId;

    /**
     * @var array<string, mixed>
     */
    public array $insertArguments = [];

    public function mount(mixed $resourceId): void
    {
        $this->resourceId = $resourceId;
        $this->form->fill($this->insertArguments);
    }

    public function insertAction(): Action
    {
        return Action::make('insert')
            ->label(__('resource-browser::browser.actions.insert_resource', ['label' => $this->getResourceBrowser()->getLabel()]))
            ->color('primary')
            ->submit('insert');
    }

    public function insert(): void
    {
        $this->dispatch('resource-browser-insert', [
            'browserResourceType' => $this->browserResourceType,
            'resource_id' => $this->resourceId,
            'args' => $this->form->getState(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        $resource = $this->getResourceBrowser()->getResource($this->resourceId);

        return $this->getResourceBrowser()->getBrowserSelectForm($schema, $resource)
            ->statePath('insertArguments');
    }

    public function back(): void
    {
        $this->dispatch('back')->to(ResourceBrowser::class);
    }

    public function edit(): void
    {
        $this->dispatch('set-edit', $this->resourceId)->to(ResourceBrowser::class);
    }

    public function canEdit(): bool
    {
        return (bool) $this->getResourceBrowser()->canEdit(
            $this->getResourceBrowser()->getResource($this->resourceId)
        );
    }

    public function render(): View
    {
        return view('resource-browser::livewire.resource-insert', [
            'canEdit' => $this->canEdit(),
        ]);
    }
}
