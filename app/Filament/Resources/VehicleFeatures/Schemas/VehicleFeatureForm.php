<?php

namespace App\Filament\Resources\VehicleFeatures\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VehicleFeatureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Característica')->columns(2)->schema([
                    Select::make('category')->required()->options([
                        'Segurança' => 'Segurança',
                        'Conforto' => 'Conforto',
                        'Tecnologia' => 'Tecnologia',
                        'Assistência à condução' => 'Assistência à condução',
                        'Exterior' => 'Exterior',
                        'Interior' => 'Interior',
                        'Performance' => 'Performance',
                        'Elétricos/Híbridos' => 'Elétricos/Híbridos',
                        'Documentação/garantia' => 'Documentação/garantia',
                    ]),
                    TextInput::make('slug')->required()->unique(ignoreRecord: true),
                    TextInput::make('sort_order')->numeric()->default(0),
                    Toggle::make('is_active')->default(true),
                ]),
                Repeater::make('translations')->relationship()->columns(2)->schema([
                    Select::make('locale')->options(['pt' => 'PT', 'en' => 'EN'])->required(),
                    TextInput::make('name')->required()->maxLength(255),
                ])->defaultItems(2),
            ]);
    }
}
