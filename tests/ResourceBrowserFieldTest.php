<?php

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Testing\TestAction;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Livewire\Component;
use Livewire\Livewire;
use Webwizardsusa\ResourceBrowser\Form\ResourceBrowserField;
use Webwizardsusa\ResourceBrowser\Tests\Fixtures\Note;
use Webwizardsusa\ResourceBrowser\Tests\Fixtures\NoteBrowser;
use Webwizardsusa\ResourceBrowser\Tests\Fixtures\User;
use Webwizardsusa\ResourceBrowser\Tests\TestCase;

uses(TestCase::class);

class ResourceBrowserFieldForm extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    /**
     * @var array<string, mixed>|null
     */
    public ?array $inserted = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                ResourceBrowserField::make('note_id')
                    ->resourceBrowser(NoteBrowser::class)
                    ->onInsert(function (array $arguments): void {
                        $this->inserted = $arguments;
                    }),
            ])
            ->statePath('data');
    }

    public function render(): string
    {
        return '<div>{{ $this->form }}</div>';
    }
}

beforeEach(function (): void {
    $this->actingAs(User::query()->create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'secret']));
});

it('labels the browse action with the resource', function (): void {
    Livewire::test(ResourceBrowserFieldForm::class)
        ->assertSee('Browse Notes');
});

it('takes the picked record as its state and calls onInsert', function (): void {
    $note = Note::query()->create(['title' => 'First']);
    $arguments = ['browserResourceType' => NoteBrowser::class, 'resource_id' => $note->getKey(), 'args' => ['caption' => 'A caption']];

    Livewire::test(ResourceBrowserFieldForm::class)
        ->callAction(TestAction::make('open-browser')->schemaComponent('note_id'), arguments: [$arguments])
        ->assertSet('data.note_id', $note->getKey())
        ->assertSet('inserted', $arguments);
});

it('shows a preview of the picked record', function (): void {
    $note = Note::query()->create(['title' => 'A picked note']);

    Livewire::test(ResourceBrowserFieldForm::class)
        ->set('data.note_id', $note->getKey())
        ->assertSee('A picked note');
});
