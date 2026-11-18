---
title: Installation
weight: 20
description: Install the package and, optionally, publish its views and translations.
---

```bash
composer require webwizardsusa/filament-resource-browser
```

The service provider is registered by package discovery. There is no config file, migration or panel plugin: write a
[BrowsableResource](browsable-resources.md) and use it in a [field or an action](field-and-action.md).

## Translations and views (optional)

The labels live in the `resource-browser` translation namespace, and the views in the `resource-browser` view
namespace. Publish them to change the wording or the markup:

```bash
php artisan vendor:publish --tag=resource-browser-translations
php artisan vendor:publish --tag=resource-browser-views
```
