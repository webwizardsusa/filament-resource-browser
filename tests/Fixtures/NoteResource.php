<?php

namespace Webwizardsusa\ResourceBrowser\Tests\Fixtures;

use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NoteResource extends Resource
{
    protected static ?string $model = Note::class;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->default('Untitled')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('title'),
        ]);
    }
}
