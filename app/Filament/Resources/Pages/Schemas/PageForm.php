<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Publicação')->columns(4)->schema([
                Select::make('template')->options([
                    'home' => 'Homepage',
                    'default' => 'Página',
                    'buy' => 'Comprar',
                    'rent' => 'Alugar',
                    'financing' => 'Financiamento',
                    'contact' => 'Contactos',
                    'faq' => 'FAQ',
                    'legal' => 'Legal',
                ])->default('default')->required(),
                Select::make('status')->options(['draft' => 'Rascunho', 'published' => 'Publicado'])->default('draft')->required(),
                DateTimePicker::make('published_at'),
                Toggle::make('is_system')->label('Sistema'),
                TextInput::make('sort_order')->numeric()->default(0),
                SpatieMediaLibraryFileUpload::make('page_hero')->collection('page_hero')->image()->label('Imagem principal')->columnSpan(3),
            ]),
            Repeater::make('translations')->relationship()->columns(2)->schema([
                Select::make('locale')->options(['pt' => 'PT', 'en' => 'EN'])->required(),
                TextInput::make('title')->required()->maxLength(255)->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),
                TextInput::make('slug')->required()->maxLength(255),
                TextInput::make('meta_title')->maxLength(255),
                Textarea::make('meta_description')->rows(3),
                RichEditor::make('content')->columnSpanFull(),
            ])->defaultItems(2)->columnSpanFull(),
        ]);
    }
}
