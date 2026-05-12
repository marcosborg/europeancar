<?php

namespace App\Filament\Resources\VehicleFeatures;

use App\Filament\Resources\VehicleFeatures\Pages\CreateVehicleFeature;
use App\Filament\Resources\VehicleFeatures\Pages\EditVehicleFeature;
use App\Filament\Resources\VehicleFeatures\Pages\ListVehicleFeatures;
use App\Filament\Resources\VehicleFeatures\Schemas\VehicleFeatureForm;
use App\Filament\Resources\VehicleFeatures\Tables\VehicleFeaturesTable;
use App\Models\VehicleFeature;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VehicleFeatureResource extends Resource
{
    protected static ?string $model = VehicleFeature::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrench;

    public static function form(Schema $schema): Schema
    {
        return VehicleFeatureForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VehicleFeaturesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVehicleFeatures::route('/'),
            'create' => CreateVehicleFeature::route('/create'),
            'edit' => EditVehicleFeature::route('/{record}/edit'),
        ];
    }
}
