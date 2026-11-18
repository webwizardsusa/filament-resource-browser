<?php

namespace Webwizardsusa\ResourceBrowser\Concerns;

use Closure;
use Webwizardsusa\ResourceBrowser\BrowsableResource;

/**
 * Gives an action, field or Livewire component a BrowsableResource, built from its class with `$browserOptions`.
 */
trait ComponentInteractsWithBrowser
{
    /**
     * @var class-string<BrowsableResource>|Closure
     */
    public string|Closure $browserResourceType;

    protected BrowsableResource $resourceBrowserInstance;

    /**
     * @var array<string, mixed>
     */
    public array $browserOptions = [];

    /**
     * @param  class-string<BrowsableResource>|Closure  $browserClass
     */
    public function resourceBrowser(string|Closure $browserClass): static
    {
        $this->browserResourceType = $browserClass;

        return $this;
    }

    public function getResourceBrowser(): BrowsableResource
    {
        if (! isset($this->resourceBrowserInstance)) {
            $class = $this->evaluate($this->browserResourceType);

            if (is_string($class)) {
                $class = app($class, ['options' => $this->browserOptions]);
            }

            $this->resourceBrowserInstance = $class;
        }

        return $this->resourceBrowserInstance;
    }
}
