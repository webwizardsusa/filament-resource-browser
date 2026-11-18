<?php

namespace Webwizardsusa\ResourceBrowser\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Webwizardsusa\ResourceBrowser\Concerns\ComponentInteractsWithBrowser;

/**
 * The browser in the modal: the BrowsableResource's table, then the select form once a record is picked, or the
 * resource form to add or edit one.
 */
class ResourceBrowser extends Component implements HasActions, HasSchemas, HasTable
{
    use ComponentInteractsWithBrowser;
    use EvaluatesClosures;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;
    use WithoutUrlPagination;

    public string $mode = 'browse';

    /**
     * @var array<string, mixed>
     */
    public array $insertArguments = [];

    public mixed $selected = null;

    public bool $adding = false;

    public mixed $editing = null;

    public function setPage(int|string $page, ?string $pageName = null): void
    {
        $this->paginators[$pageName] = $page;
        $this->dispatch('scrollToTopOfTable')->self();
    }

    /**
     * @return array<string, mixed>
     */
    protected function queryString(): array
    {
        return [];
    }

    public function table(Table $table): Table
    {
        return $this->getResourceBrowser()->getBrowserTable($table)
            ->recordAction('insert')
            ->toolbarActions([])
            ->headerActions([])
            ->recordActions([
                Action::make('insert')
                    ->label('')
                    ->action(fn (mixed $record, ResourceBrowser $livewire) => $livewire->selectRecord($record->getKey())),
                Action::make('edit')
                    ->label(__('resource-browser::browser.actions.edit'))
                    ->hidden(fn (mixed $record): bool => ! $this->getResourceBrowser()->canEdit($record))
                    ->action(fn (mixed $record, ResourceBrowser $livewire) => $livewire->editRecord($record->getKey())),
                ...$this->getResourceBrowser()->getExtraRecordActions(),
            ]);
    }

    #[On('selectRecord')]
    public function selectRecord(int|string $id, ?string $type = null): void
    {
        $this->mode = 'insert';
        $this->adding = false;
        $this->editing = null;
        $this->selected = $id;
    }

    #[On('set-edit')]
    public function editRecord(mixed $id): void
    {
        $this->editing = $id;
        $this->adding = false;
        $this->selected = false;
    }

    /**
     * @param  array{browserResourceType: string, resource_id: mixed, args: array<string, mixed>}  $args
     */
    #[On('resource-browser-insert')]
    public function insert(array $args): void
    {
        $this->dispatch('insert', $args);
    }

    #[On('back')]
    public function back(): void
    {
        $this->selected = null;
        $this->editing = null;
        $this->adding = false;
    }

    #[On('show-resource-modal-add')]
    public function showAdd(): void
    {
        $this->adding = true;
        $this->selected = null;
    }

    public function render(): View
    {
        return view('resource-browser::livewire.resource-browser');
    }
}
