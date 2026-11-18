<?php

namespace Webwizardsusa\ResourceBrowser\Concerns;

use Filament\Resources\Resource as FilamentResource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Webwizardsusa\ResourceBrowser\BrowsableResource;

/**
 * Takes a BrowsableResource's label, table, form, record lookup, saving and authorization from a Filament resource.
 *
 * @mixin BrowsableResource
 */
trait BrowsableFromResource
{
    /**
     * @return class-string<FilamentResource>
     */
    abstract public function resourceClass(): string;

    public function getLabel(): string
    {
        return $this->resourceClass()::getTitleCaseModelLabel();
    }

    public function getResource(mixed $id): ?Model
    {
        return $this->resourceClass()::getEloquentQuery()->find($id);
    }

    public function getBrowserTable(Table $table): Table
    {
        return $this->resourceClass()::table($table)
            ->query($this->resourceClass()::getEloquentQuery());
    }

    public function resourceForm(Schema $schema): Schema
    {
        return $this->resourceClass()::form($schema);
    }

    public function canCreate(): ?bool
    {
        return $this->resourceClass()::canCreate();
    }

    public function canEdit(mixed $resource): ?bool
    {
        return $this->resourceClass()::canEdit($resource);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function saveResource(array $data, mixed $resource = null): mixed
    {
        $resource ??= app($this->resourceClass()::getModel());

        $resource->fill($data);
        $resource->save();

        return $resource;
    }
}
