<?php

namespace Webwizardsusa\ResourceBrowser;

use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Webwizardsusa\ResourceBrowser\Livewire\ResourceBrowser;

use function Filament\Support\locale_has_pluralization;

/**
 * Describes what the browser shows and does for one kind of record: its table, the form to add and edit records, the
 * form shown once a record is picked, and the preview a ResourceBrowserField shows. Use `BrowsableFromResource` to
 * take most of it from a Filament resource.
 */
abstract class BrowsableResource
{
    /**
     * @param  array<string, mixed>  $options  from the action or field's `->options()`
     */
    public function __construct(protected array $options = []) {}

    public function getOption(string $key, mixed $defaultValue = null): mixed
    {
        return Arr::get($this->options, $key, $defaultValue);
    }

    abstract public function getLabel(): string;

    public function getPluralLabel(): string
    {
        if (locale_has_pluralization()) {
            return Str::plural($this->getLabel());
        }

        return $this->getLabel();
    }

    /**
     * The Livewire component the modal shows.
     *
     * @return class-string
     */
    public function browserComponent(): string
    {
        return ResourceBrowser::class;
    }

    abstract public function getResource(mixed $id): mixed;

    abstract public function getBrowserTable(Table $table): Table;

    /**
     * The form shown once a record is picked, such as alt text or a caption for an image. Its state is passed to the
     * action as `args`.
     */
    abstract public function getBrowserSelectForm(Schema $schema, mixed $resource): Schema;

    /**
     * @param  array<string, mixed>  $data
     */
    abstract public function saveResource(array $data, mixed $resource = null): mixed;

    abstract public function resourceForm(Schema $schema): Schema;

    abstract public function canCreate(): ?bool;

    abstract public function canEdit(mixed $resource): ?bool;

    abstract public function fieldPreview(mixed $resource): mixed;

    /**
     * Extra record actions to add to the browser table, alongside the built-in insert/edit actions.
     *
     * @return array<int, Action>
     */
    public function getExtraRecordActions(): array
    {
        return [];
    }
}
