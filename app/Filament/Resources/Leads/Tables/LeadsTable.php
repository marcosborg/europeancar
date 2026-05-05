<?php

namespace App\Filament\Resources\Leads\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class LeadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Data')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('phone')->searchable(),
                TextColumn::make('type')->badge()->sortable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('vehicle.sku')->label('Viatura')->searchable(),
            ])
            ->filters([
                SelectFilter::make('type')->options(['contact' => 'Contacto', 'financing' => 'Financiamento', 'rental' => 'Aluguer', 'reservation' => 'Reserva']),
                SelectFilter::make('status')->options(['new' => 'Novo', 'reviewing' => 'Em análise', 'contacted' => 'Contactado', 'closed' => 'Fechado', 'lost' => 'Perdido']),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
