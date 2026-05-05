<?php

namespace App\Filament\Resources\SocialExports;

use App\Filament\Resources\SocialExports\Pages\CreateSocialExport;
use App\Filament\Resources\SocialExports\Pages\EditSocialExport;
use App\Filament\Resources\SocialExports\Pages\ListSocialExports;
use App\Filament\Resources\SocialExports\Schemas\SocialExportForm;
use App\Filament\Resources\SocialExports\Tables\SocialExportsTable;
use App\Models\SocialExport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SocialExportResource extends Resource
{
    protected static ?string $model = SocialExport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return SocialExportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SocialExportsTable::configure($table);
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
            'index' => ListSocialExports::route('/'),
            'create' => CreateSocialExport::route('/create'),
            'edit' => EditSocialExport::route('/{record}/edit'),
        ];
    }
}
