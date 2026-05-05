<?php

namespace App\Filament\Resources\Vehicles\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class VehiclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sku')->label('Ref.')->searchable()->sortable(),
                TextColumn::make('brand.name')->label('Marca')->searchable()->sortable(),
                TextColumn::make('carModel.name')->label('Modelo')->searchable()->sortable(),
                TextColumn::make('version')->searchable()->toggleable(),
                TextColumn::make('year')->sortable(),
                TextColumn::make('mileage')->label('Km')->numeric()->sortable(),
                TextColumn::make('sale_price')->money('EUR')->sortable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('type')->badge()->sortable(),
                IconColumn::make('featured')->boolean()->label('Destaque'),
                TextColumn::make('created_at')->dateTime('d/m/Y H:i')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'draft' => 'Rascunho',
                    'published' => 'Publicado',
                    'reserved' => 'Reservado',
                    'sold' => 'Vendido',
                    'rented' => 'Alugado',
                    'unavailable' => 'Indisponível',
                ]),
                SelectFilter::make('type')->options(['sale' => 'Venda', 'rent' => 'Aluguer', 'sale_rent' => 'Venda e aluguer']),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('socialPreview')
                    ->label('Preview social')
                    ->icon('heroicon-o-photo')
                    ->url(fn ($record) => route('admin.social-preview', ['vehicle' => $record, 'locale' => 'pt', 'format' => 'square']))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
