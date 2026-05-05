<?php

namespace App\Filament\Widgets;

use App\Models\Vehicle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentVehicles extends TableWidget
{
    protected static ?string $heading = 'Viaturas recentes';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Vehicle::query()->with(['brand', 'carModel'])->latest()->limit(8))
            ->columns([
                TextColumn::make('sku')->label('Ref.')->searchable(),
                TextColumn::make('brand.name')->label('Marca'),
                TextColumn::make('carModel.name')->label('Modelo'),
                TextColumn::make('status')->badge(),
                TextColumn::make('created_at')->dateTime('d/m/Y H:i'),
            ]);
    }
}
