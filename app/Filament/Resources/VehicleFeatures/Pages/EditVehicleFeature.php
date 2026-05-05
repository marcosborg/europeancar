<?php

namespace App\Filament\Resources\VehicleFeatures\Pages;

use App\Filament\Resources\VehicleFeatures\VehicleFeatureResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVehicleFeature extends EditRecord
{
    protected static string $resource = VehicleFeatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
