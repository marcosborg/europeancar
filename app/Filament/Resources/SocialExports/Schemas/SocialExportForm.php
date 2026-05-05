<?php

namespace App\Filament\Resources\SocialExports\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SocialExportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Exportação')->columns(2)->schema([
                Select::make('vehicle_id')->relationship('vehicle', 'sku')->searchable()->preload()->required(),
                Select::make('format')->options(['square' => '1080x1080', 'story' => '1080x1920', 'csv' => 'CSV'])->default('square')->required(),
                Select::make('locale')->options(['pt' => 'PT', 'en' => 'EN'])->default('pt')->required(),
                Textarea::make('copy')->columnSpanFull(),
            ]),
        ]);
    }
}
