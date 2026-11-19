<?php

namespace Webwizardsusa\ResourceBrowser\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use Webwizardsusa\ResourceBrowser\Concerns\ComponentInteractsWithBrowser;

/**
 * Opens a BrowsableResource's browser in a full-width modal, to pick a record, or add or edit one. Picking a record
 * calls the action with `[['browserResourceType' => …, 'resource_id' => …, 'args' => […]]]`, where `args` is the
 * state of the browser's select form.
 */
class ResourceBrowserAction extends Action
{
    use ComponentInteractsWithBrowser;

    protected mixed $selected = null;

    /**
     * @var array<string, mixed>|Closure
     */
    protected array|Closure $options = [];

    /**
     * @var array<string, mixed>|Closure
     */
    protected array|Closure $insertArguments = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->modalWidth(Width::Full)
            ->modalHeading(fn (): HtmlString => new HtmlString(view('resource-browser::modal-header', [
                'label' => __('resource-browser::browser.browse', ['label' => $this->getResourceBrowser()->getPluralLabel()]),
                'canCreate' => $this->getResourceBrowser()->canCreate(),
            ])->render()))
            ->stickyModalHeader()
            ->stickyModalFooter()
            ->modalSubmitAction(false)
            ->modalCancelAction(false)
            ->modalContent(fn (array $arguments): View => $this->renderWindow($arguments))
            ->action(function (array $arguments = []): void {});
    }

    /**
     * The record the browser opens on, by its key.
     */
    public function select(mixed $selection): static
    {
        $this->selected = $selection;

        return $this;
    }

    public function getSelected(): mixed
    {
        return $this->evaluate($this->selected);
    }

    /**
     * @param  array<string, mixed>  $arguments
     */
    public function renderWindow(array $arguments = []): View
    {
        return view('resource-browser::browser-wrapper', [
            'component' => $this->getResourceBrowser()->browserComponent(),
            'browserResourceType' => get_class($this->getResourceBrowser()),
            'selected' => $this->getSelected(),
            'insertArguments' => $this->getInsertArguments(),
            'options' => $this->getOptions(),
            'action' => $this,
        ]);
    }

    /**
     * The initial state of the browser's select form.
     *
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
     * Options passed to the BrowsableResource's constructor.
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

    public function resource(string|Closure $resource): static
    {
        return $this->resourceBrowser($resource);
    }
}
