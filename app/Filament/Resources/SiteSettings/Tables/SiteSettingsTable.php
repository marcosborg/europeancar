<?php

namespace App\Filament\Resources\SiteSettings\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SiteSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('site_name')->searchable(),
                TextColumn::make('primary_email'),
                TextColumn::make('phone'),
                TextColumn::make('updated_at')->dateTime('d/m/Y H:i'),
            ])
            ->recordActions([EditAction::make()]);
    }
}
