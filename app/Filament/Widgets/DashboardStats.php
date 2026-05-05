<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use App\Models\Vehicle;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total viaturas', Vehicle::query()->count())->icon('heroicon-o-truck'),
            Stat::make('Publicadas', Vehicle::query()->where('status', 'published')->count())->color('success'),
            Stat::make('Vendidas', Vehicle::query()->where('status', 'sold')->count())->color('warning'),
            Stat::make('Aluguer disponível', Vehicle::query()->whereIn('type', ['rent', 'sale_rent'])->where('rental_availability', 'available')->count()),
            Stat::make('Contactos pendentes', Lead::query()->where('status', 'new')->count())->color('danger'),
            Stat::make('Financiamento', Lead::query()->where('type', 'financing')->count()),
            Stat::make('Aluguer/reserva', Lead::query()->whereIn('type', ['rental', 'reservation'])->count()),
        ];
    }
}
