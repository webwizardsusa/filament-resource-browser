<?php

namespace Webwizardsusa\ResourceBrowser\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component as SchemaComponent;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Support\Enums\Alignment;
use Livewire\Component;
use Webwizardsusa\ResourceBrowser\Concerns\ComponentInteractsWithBrowser;

/**
 * Adds a record, or edits the one with `$resourceId`, with the BrowsableResource's resource form, then picks it.
 *
 * @property Schema $form
 */
class ResourceForm extends Component implements HasActions, HasSchemas
{
    use ComponentInteractsWithBrowser;
    use EvaluatesClosures;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public mixed $resourceId = null;

    /**
     * @var array<string, mixed>
     */
    public array $formData = [];

    public function mount(): void
    {
        if ($this->resourceId) {
            $model = $this->getResourceBrowser()->getResource($this->resourceId);
            $this->form->fill($model->attributesToArray());
            $this->form->model($model);

            return;
        }

        $this->form->fill();
    }

    public function saveAction(): Action
    {
        return Action::make('save')
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $resource = null;

        if ($this->resourceId) {
            $resource = $this->getResourceBrowser()->getResource($this->resourceId);
        }

        $record = $this->getResourceBrowser()->saveResource($data, $resource);

        Notification::make()
            ->success()
            ->title(__($this->resourceId ? 'resource-browser::browser.notifications.updated' : 'resource-browser::browser.notifications.created', [
                'label' => $this->getResourceBrowser()->getLabel(),
            ]))
            ->send();

        $this->dispatch('selectRecord', $record->getKey());
    }

    public function insertAction(): Action
    {
        return Action::make('insert')
            ->label(__('resource-browser::browser.actions.insert'))
            ->color('gray')
            ->hidden(fn (): bool => ! $this->resourceId)
            ->action(fn () => $this->dispatch('selectRecord', $this->resourceId));
    }

    public function backAction(): Action
    {
        return Action::make('browse')
            ->label(__('resource-browser::browser.actions.browse'))
            ->color('gray')
            ->action(fn () => $this->dispatch('back'));
    }

    /**
     * @return array<Action>
     */
    protected function getFormActions(): array
    {
        return [
            $this->saveAction(),
            $this->insertAction(),
            $this->backAction(),
        ];
    }

    public function getFormActionsContentComponent(): SchemaComponent
    {
        return Actions::make($this->getFormActions())
            ->alignment(Alignment::Start)
            ->fullWidth(false)
            ->sticky(false)
            ->key('form-actions');
    }

    public function form(Schema $schema): Schema
    {
        $schema->columns(1);

        if ($this->resourceId) {
            $schema->model($this->getResourceBrowser()->getResource($this->resourceId));
        }

        return $this->getResourceBrowser()->resourceForm($schema)
            ->statePath('formData');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    EmbeddedSchema::make('form'),
                ])
                    ->id('insert-form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        $this->getFormActionsContentComponent(),
                    ]),
            ]);
    }

    public function render(): string
    {
        return <<<'blade'
            <div>
                {{ $this->content }}
            </div>
            blade;
    }
}
