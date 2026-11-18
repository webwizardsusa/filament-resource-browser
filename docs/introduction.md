---
title: Introduction
weight: 10
description: What Filament Resource Browser does, a short example, and what it needs.
---

Filament Resource Browser lets one part of your panel reuse another resource's records. It opens a modal with the
resource's table; picking a record shows a short form for that use of it (alt text or a caption for an image, say),
and inserting returns the record and the form's state. From the same modal you can add a record, or edit one, with the
resource's own form, and the saved record is picked straight away.

You describe each kind of record once, in a **BrowsableResource**, and use it in two ways:

- a **ResourceBrowserField**, a form field whose state is the picked record's key, with a preview of the record;
- a **ResourceBrowserAction**, to open the browser from anywhere an action goes, such as a rich editor's toolbar, and
  do what you like with the picked record.

## A short example

```php
namespace App\Filament\Browsers;

use App\Filament\Resources\Images\ImageResource;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Webwizardsusa\ResourceBrowser\BrowsableResource;
use Webwizardsusa\ResourceBrowser\Concerns\BrowsableFromResource;

class ImageBrowser extends BrowsableResource
{
    use BrowsableFromResource;

    public function resourceClass(): string
    {
        return ImageResource::class;
    }

    public function getBrowserSelectForm(Schema $schema, mixed $resource): Schema
    {
        return $schema->components([
            TextInput::make('alt')->label('Alt text'),
        ]);
    }

    public function fieldPreview(mixed $resource): mixed
    {
        return new HtmlString('<img src="'.e($resource?->url).'" alt="">');
    }
}
```

```php
use Webwizardsusa\ResourceBrowser\Form\ResourceBrowserField;

ResourceBrowserField::make('image_id')
    ->resourceBrowser(ImageBrowser::class);
```

## Requirements

- PHP 8.3 or later
- Filament 5

## Where to go next

- [Installation](installation.md)
- [Browsable resources](browsable-resources.md)
- [The field and the action](field-and-action.md)
