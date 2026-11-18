<?php

namespace Webwizardsusa\ResourceBrowser\Tests\Fixtures;

use Filament\Http\Middleware\Authenticate;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->resources([NoteResource::class])
            ->middleware([EncryptCookies::class, StartSession::class, ShareErrorsFromSession::class])
            ->authMiddleware([Authenticate::class]);
    }
}
