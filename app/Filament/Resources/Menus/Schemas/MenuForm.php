<?php

namespace App\Filament\Resources\Menus\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Menu')->columns(4)->schema([
                Select::make('location')->options(['main' => 'Principal', 'footer' => 'Footer', 'legal' => 'Legal'])->required(),
                Select::make('locale')->options(['pt' => 'PT', 'en' => 'EN'])->required(),
                TextInput::make('name')->required(),
                Toggle::make('is_active')->default(true),
            ]),
            Repeater::make('items')->relationship()->orderColumn('sort_order')->columns(4)->schema([
                TextInput::make('label')->required(),
                TextInput::make('url')->label('URL'),
                Select::make('page_id')->relationship('page.currentTranslation', 'title')->searchable()->preload(),
                Toggle::make('open_in_new_tab')->label('Nova janela'),
                Toggle::make('is_active')->default(true),
                TextInput::make('sort_order')->numeric()->default(0),
            ])->columnSpanFull(),
        ]);
    }
}
