# Filament Resource Browser

[![Latest version on Packagist](https://img.shields.io/packagist/v/webwizardsusa/filament-resource-browser.svg)](https://packagist.org/packages/webwizardsusa/filament-resource-browser)
[![Tests](https://github.com/webwizardsusa/filament-resource-browser/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/webwizardsusa/filament-resource-browser/actions/workflows/tests.yml)
[![Total downloads](https://img.shields.io/packagist/dt/webwizardsusa/filament-resource-browser.svg)](https://packagist.org/packages/webwizardsusa/filament-resource-browser/stats)
[![PHP version](https://img.shields.io/packagist/dependency-v/webwizardsusa/filament-resource-browser/php.svg)](https://packagist.org/packages/webwizardsusa/filament-resource-browser)
[![License](https://img.shields.io/packagist/l/webwizardsusa/filament-resource-browser.svg)](https://github.com/webwizardsusa/filament-resource-browser/blob/main/LICENSE.md)

Reuse one Filament resource from another: browse its records in a modal, pick one to insert, or add or edit one
without leaving the form you're in. Pick an image for a post from the images resource, for example, filling in its
alt text as you insert it.

Requires PHP 8.3+ and Filament 5.

```bash
composer require webwizardsusa/filament-resource-browser
```

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

    public function getBrowserSelectForm(Schema $schema, mixed $resource): Schema
    {
        return $schema->components([TextInput::make('alt')]);
    }

    public function fieldPreview(mixed $resource): mixed
    {
        return new HtmlString('<img src="'.e($resource?->url).'" alt="">');
    }
}
```

```php
use Webwizardsusa\ResourceBrowser\Form\ResourceBrowserField;

ResourceBrowserField::make('image_id')->resourceBrowser(ImageBrowser::class);
```

## Documentation

Read the full documentation in the
[`docs` folder](https://github.com/webwizardsusa/filament-resource-browser/tree/main/docs).

## License

The MIT License (MIT). See [LICENSE.md](LICENSE.md).
