<?php

namespace App\Filament\Resources\Pages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('translations.title')->label('Título')->searchable(),
                TextColumn::make('template')->badge()->sortable(),
                TextColumn::make('status')->badge()->sortable(),
                IconColumn::make('is_system')->boolean()->label('Sistema'),
                TextColumn::make('published_at')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('template')->options(['home' => 'Homepage', 'default' => 'Página', 'legal' => 'Legal']),
                SelectFilter::make('status')->options(['draft' => 'Rascunho', 'published' => 'Publicado']),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
