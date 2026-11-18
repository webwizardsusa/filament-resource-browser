<?php

namespace Webwizardsusa\ResourceBrowser\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Schema;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Webwizardsusa\ResourceBrowser\ResourceBrowserServiceProvider;
use Webwizardsusa\ResourceBrowser\Tests\Fixtures\AdminPanelProvider;
use Webwizardsusa\ResourceBrowser\Tests\Fixtures\User;

/**
 * Boots Filament and Livewire around the package, with an `admin` panel that has the fixture NoteResource, and creates
 * a `notes` table to browse and a `users` table.
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @param  Application  $app
     * @return array<class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            BladeIconsServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            LivewireServiceProvider::class,
            SupportServiceProvider::class,
            ActionsServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            NotificationsServiceProvider::class,
            SchemasServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            FilamentServiceProvider::class,
            AdminPanelProvider::class,
            ResourceBrowserServiceProvider::class,
        ];
    }

    /**
     * @param  Application  $app
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(str_repeat('a', 32)));
        $app['config']->set('database.default', 'testing');
        $app['config']->set('auth.providers.users.model', User::class);
    }

    protected function defineDatabaseMigrations(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('notes', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->timestamps();
        });
    }
}
