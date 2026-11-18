---
title: The field and the action
weight: 40
description: Pick a record into a form field, or open the browser from any action.
---

## ResourceBrowserField

A form field whose state is the key of the picked record. It shows a **Browse …** button and, once a record is picked,
the BrowsableResource's `fieldPreview()`.

```php
use Webwizardsusa\ResourceBrowser\Form\ResourceBrowserField;

ResourceBrowserField::make('image_id')
    ->resourceBrowser(ImageBrowser::class)
    ->insertArguments(['alt' => ''])
    ->onInsert(function (array $arguments, Set $set): void {
        $set('image_alt', $arguments['args']['alt'] ?? null);
    })
    ->preview(fn (ResourceBrowserField $component) => view('images.preview', ['image' => $component->getResource()]));
```

- `insertArguments()` fills the select form when a record is picked.
- `onInsert()` runs after a record is picked, with `$arguments`: `resource_id`, `browserResourceType` and the select
  form's state as `args`.
- `preview()` replaces `fieldPreview()`. Return `false` to fall back to it.

## ResourceBrowserAction

The field is built on this action, which you can use anywhere an action goes. When a record is inserted, the action
runs with the inserted record as its first argument:

```php
use Webwizardsusa\ResourceBrowser\Actions\ResourceBrowserAction;

ResourceBrowserAction::make('insertImage')
    ->resourceBrowser(ImageBrowser::class)
    ->select(fn () => $currentImageId)
    ->action(function (array $arguments): void {
        ['resource_id' => $id, 'args' => $args] = $arguments[0];

        // Insert the image…
    });
```

- `select()` opens the browser on a record, as the field does with its state.
- `insertArguments()` and `options()` work as on the field.
