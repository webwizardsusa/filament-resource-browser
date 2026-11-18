<?php

use Livewire\Livewire;
use Webwizardsusa\ResourceBrowser\Livewire\ResourceBrowser;
use Webwizardsusa\ResourceBrowser\Livewire\ResourceForm;
use Webwizardsusa\ResourceBrowser\Livewire\ResourceSelect;
use Webwizardsusa\ResourceBrowser\Tests\Fixtures\Note;
use Webwizardsusa\ResourceBrowser\Tests\Fixtures\NoteBrowser;
use Webwizardsusa\ResourceBrowser\Tests\Fixtures\User;
use Webwizardsusa\ResourceBrowser\Tests\TestCase;

uses(TestCase::class);

beforeEach(function (): void {
    $this->actingAs(User::query()->create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => 'secret']));
});

it('lists the records of the resource', function (): void {
    $notes = collect(['First', 'Second'])->map(fn (string $title): Note => Note::query()->create(['title' => $title]));

    Livewire::test(ResourceBrowser::class, ['browserResourceType' => NoteBrowser::class])
        ->assertCanSeeTableRecords($notes)
        ->assertSee('First');
});

it('shows the select form once a record is picked, and the browser again on back', function (): void {
    $note = Note::query()->create(['title' => 'First']);

    Livewire::test(ResourceBrowser::class, ['browserResourceType' => NoteBrowser::class])
        ->call('selectRecord', $note->getKey())
        ->assertSet('selected', $note->getKey())
        ->assertSeeLivewire(ResourceSelect::class)
        ->call('back')
        ->assertSet('selected', null)
        ->assertDontSeeLivewire(ResourceSelect::class);
});

it('shows the resource form to add a record', function (): void {
    Livewire::test(ResourceBrowser::class, ['browserResourceType' => NoteBrowser::class])
        ->call('showAdd')
        ->assertSet('adding', true)
        ->assertSeeLivewire(ResourceForm::class);
});

it('passes the inserted record up to the modal', function (): void {
    $args = ['browserResourceType' => NoteBrowser::class, 'resource_id' => 1, 'args' => ['caption' => 'A caption']];

    Livewire::test(ResourceBrowser::class, ['browserResourceType' => NoteBrowser::class])
        ->call('insert', $args)
        ->assertDispatched('insert', $args);
});

it('inserts the picked record with the select form state', function (): void {
    $note = Note::query()->create(['title' => 'First']);

    Livewire::test(ResourceSelect::class, [
        'browserResourceType' => NoteBrowser::class,
        'resourceId' => $note->getKey(),
        'insertArguments' => ['caption' => 'Default caption'],
    ])
        ->assertSet('insertArguments.caption', 'Default caption')
        ->set('insertArguments.caption', 'A caption')
        ->call('insert')
        ->assertDispatched('resource-browser-insert', [
            'browserResourceType' => NoteBrowser::class,
            'resource_id' => $note->getKey(),
            'args' => ['caption' => 'A caption'],
        ]);
});

it('fills the resource form with its defaults to add a record', function (): void {
    Livewire::test(ResourceForm::class, ['browserResourceType' => NoteBrowser::class])
        ->assertSet('formData.title', 'Untitled');
});

it('creates a record and picks it', function (): void {
    $component = Livewire::test(ResourceForm::class, ['browserResourceType' => NoteBrowser::class])
        ->set('formData.title', 'New note')
        ->call('save')
        ->assertHasNoFormErrors();

    $note = Note::query()->sole();

    expect($note->title)->toBe('New note');
    $component->assertDispatched('selectRecord', $note->getKey())
        ->assertNotified('The Note was created');
});

it('updates a record and picks it', function (): void {
    $note = Note::query()->create(['title' => 'Old title']);

    Livewire::test(ResourceForm::class, ['browserResourceType' => NoteBrowser::class, 'resourceId' => $note->getKey()])
        ->assertSet('formData.title', 'Old title')
        ->set('formData.title', 'New title')
        ->call('save')
        ->assertDispatched('selectRecord', $note->getKey())
        ->assertNotified('The Note was updated');

    expect($note->refresh()->title)->toBe('New title');
});

it('validates the resource form', function (): void {
    Livewire::test(ResourceForm::class, ['browserResourceType' => NoteBrowser::class])
        ->set('formData.title', '')
        ->call('save')
        ->assertHasFormErrors(['title' => 'required'], 'form');

    expect(Note::query()->count())->toBe(0);
});
