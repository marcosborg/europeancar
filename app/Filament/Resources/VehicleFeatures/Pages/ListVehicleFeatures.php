<?php

namespace App\Filament\Resources\VehicleFeatures\Pages;

use App\Filament\Resources\VehicleFeatures\VehicleFeatureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVehicleFeatures extends ListRecords
{
    protected static string $resource = VehicleFeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
