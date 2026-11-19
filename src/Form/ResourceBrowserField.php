<?php

namespace Webwizardsusa\ResourceBrowser\Form;

use Closure;
use Filament\Forms\Components\Field;
use Webwizardsusa\ResourceBrowser\Actions\ResourceBrowserAction;
use Webwizardsusa\ResourceBrowser\BrowsableResource;
use Webwizardsusa\ResourceBrowser\Concerns\ComponentInteractsWithBrowser;

/**
 * A form field whose state is the key of a record picked in the browser, with a preview of that record.
 */
class ResourceBrowserField extends Field
{
    use ComponentInteractsWithBrowser {
        getResourceBrowser as buildResourceBrowser;
    }

    protected string $view = 'resource-browser::resource-browser-field';

    protected ?Closure $insertCallback = null;

    protected ResourceBrowserAction $browseAction;

    /**
     * @var array<string, mixed>|Closure
     */
    protected array|Closure $insertArguments = [];

    /**
     * @var array<string, mixed>|Closure
     */
    protected array|Closure $options = [];

    protected mixed $cachedResource;

    protected Closure $previewCallback;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registerActions([
            $this->getBrowseAction(),
        ]);
    }

    public function getBrowseAction(): ResourceBrowserAction
    {
        if (! isset($this->browseAction)) {
            $this->browseAction = ResourceBrowserAction::make('open-browser')
                ->label(fn (ResourceBrowserField $component): string => __('resource-browser::browser.browse', [
                    'label' => $component->getResourceBrowser()->getPluralLabel(),
                ]))
                ->resourceBrowser(fn (ResourceBrowserField $component) => $component->getResourceBrowser())
                ->select(fn (): mixed => $this->getState())
                ->insertArguments(fn (): array => $this->getInsertArguments())
                ->options(fn (): array => $this->getOptions())
                ->action(function (array $arguments): void {
                    $this->state($arguments[0]['resource_id'] ?? null);

                    if ($this->insertCallback) {
                        $this->evaluate($this->insertCallback, ['arguments' => $arguments[0]]);
                    }
                });
        }

        return $this->browseAction;
    }

    /**
     * Called after a record is picked, with `$arguments` (`resource_id` and the select form's state as `args`).
     */
    public function onInsert(Closure $callback): static
    {
        $this->insertCallback = $callback;

        return $this;
    }

    /**
     * @param  array<string, mixed>|Closure  $insertArguments
     */
    public function insertArguments(array|Closure $insertArguments): static
    {
        $this->insertArguments = $insertArguments;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getInsertArguments(): array
    {
        return (array) $this->evaluate($this->insertArguments);
    }

    /**
     * Options passed to the BrowsableResource's constructor, in the browser and for the field's preview.
     *
     * @param  array<string, mixed>|Closure  $options
     */
    public function options(array|Closure $options): static
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return (array) $this->evaluate($this->options);
    }

    public function getResourceBrowser(): BrowsableResource
    {
        if (! isset($this->resourceBrowserInstance)) {
            $this->browserOptions = $this->getOptions();
        }

        return $this->buildResourceBrowser();
    }

    public function getResource(): mixed
    {
        if (! isset($this->cachedResource)) {
            $this->cachedResource = null;

            if (filled($this->getState())) {
                $this->cachedResource = $this->getResourceBrowser()->getResource($this->getState());
            }
        }

        return $this->cachedResource;
    }

    /**
     * Replaces the BrowsableResource's `fieldPreview()`. Returning false falls back to it.
     */
    public function preview(Closure $previewCallback): static
    {
        $this->previewCallback = $previewCallback;

        return $this;
    }

    public function getResourcePreview(): mixed
    {
        if (isset($this->previewCallback)) {
            $results = $this->evaluate($this->previewCallback);

            if ($results !== false) {
                return $results;
            }
        }

        return $this->getResourceBrowser()->fieldPreview($this->getResource());
    }
}
