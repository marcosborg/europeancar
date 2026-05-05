<?php

namespace App\Filament\Resources\SocialExports\Pages;

use App\Filament\Resources\SocialExports\SocialExportResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSocialExport extends EditRecord
{
    protected static string $resource = SocialExportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
