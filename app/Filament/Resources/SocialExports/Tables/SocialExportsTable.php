<?php

namespace App\Filament\Resources\SocialExports\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SocialExportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vehicle.sku')->label('Viatura')->searchable(),
                TextColumn::make('format')->badge(),
                TextColumn::make('locale')->badge(),
                TextColumn::make('created_at')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('preview')
                    ->url(fn ($record) => route('admin.social-preview', ['vehicle' => $record->vehicle, 'locale' => $record->locale, 'format' => $record->format]))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
