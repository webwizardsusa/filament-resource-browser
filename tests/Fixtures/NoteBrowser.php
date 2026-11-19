<?php

namespace Webwizardsusa\ResourceBrowser\Tests\Fixtures;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Webwizardsusa\ResourceBrowser\BrowsableResource;
use Webwizardsusa\ResourceBrowser\Concerns\BrowsableFromResource;

/**
 * Browses NoteResource, with a caption to fill in once a Note is picked. The preview starts with the `prefix` option.
 */
class NoteBrowser extends BrowsableResource
{
    use BrowsableFromResource;

    public function resourceClass(): string
    {
        return NoteResource::class;
    }

    public function getBrowserSelectForm(Schema $schema, mixed $resource): Schema
    {
        return $schema->components([
            TextInput::make('caption'),
        ]);
    }

    public function fieldPreview(mixed $resource): mixed
    {
        return new HtmlString('<p>'.e($this->getOption('prefix', '')).e($resource?->title).'</p>');
    }
}
