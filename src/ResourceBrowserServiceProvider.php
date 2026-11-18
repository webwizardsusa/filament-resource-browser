<?php

namespace Webwizardsusa\ResourceBrowser;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * Registers the package's views and translations, both under the `resource-browser` namespace.
 */
class ResourceBrowserServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('resource-browser')
            ->hasTranslations()
            ->hasViews();
    }
}
