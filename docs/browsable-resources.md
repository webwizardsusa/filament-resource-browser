---
title: Browsable resources
weight: 30
description: Describe what the browser shows and does for a kind of record, from a Filament resource or by hand.
---

A `BrowsableResource` tells the browser, for one kind of record:

| Method | What it's for |
|---|---|
| `getLabel()` | The singular label, as in "Browse Images" (pluralized) and "Insert Image". |
| `getBrowserTable(Table $table)` | The table the modal lists records in. Clicking a row picks it. |
| `getResource(mixed $id)` | Finds a record by its key. |
| `getBrowserSelectForm(Schema $schema, mixed $resource)` | The form shown once a record is picked. Its state is returned as `args` on insert. |
| `resourceForm(Schema $schema)` | The form to add and edit records. |
| `saveResource(array $data, mixed $resource = null)` | Saves the resource form, creating a record when `$resource` is null. |
| `canCreate()`, `canEdit(mixed $resource)` | Whether the modal offers **Add new**, and **Edit** on a record. |
| `fieldPreview(mixed $resource)` | What a ResourceBrowserField shows for the picked record. |
| `getExtraRecordActions()` | More actions for the table's rows. |
| `browserComponent()` | The Livewire component the modal shows, if you replace the built-in one. |

## From a Filament resource

`BrowsableFromResource` takes the label, table, query, resource form, saving and authorization from a resource, so you
only name the resource and write the select form and the preview:

```php
use Webwizardsusa\ResourceBrowser\BrowsableResource;
use Webwizardsusa\ResourceBrowser\Concerns\BrowsableFromResource;

class ImageBrowser extends BrowsableResource
{
    use BrowsableFromResource;

    public function resourceClass(): string
    {
        return ImageResource::class;
    }

    // getBrowserSelectForm() and fieldPreview()
}
```

Records are listed with the resource's `table()` and `getEloquentQuery()`, and saved by filling and saving the
resource's model. Override `saveResource()` when saving needs more, such as storing an upload, and any other method
to show the browser something different from the resource's own pages.

## Options

The field and action pass `->options([...])` to the BrowsableResource's constructor. Read them with `getOption()`:

```php
public function getBrowserTable(Table $table): Table
{
    return ImageResource::table($table)
        ->query(Image::query()->where('collection', $this->getOption('collection', 'default')));
}
```
