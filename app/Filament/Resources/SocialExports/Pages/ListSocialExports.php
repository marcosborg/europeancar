<?php

namespace App\Filament\Resources\SocialExports\Pages;

use App\Filament\Resources\SocialExports\SocialExportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSocialExports extends ListRecords
{
    protected static string $resource = SocialExportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
