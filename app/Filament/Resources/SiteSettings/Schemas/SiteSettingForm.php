<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identidade')->columns(2)->schema([
                TextInput::make('site_name')->required(),
                TextInput::make('slogan')->required(),
                SpatieMediaLibraryFileUpload::make('site_logo')->collection('site_logo')->image()->label('Logótipo'),
                SpatieMediaLibraryFileUpload::make('site_favicon')->collection('site_favicon')->image()->label('Favicon'),
                SpatieMediaLibraryFileUpload::make('site_default_seo')->collection('site_default_seo')->image()->label('Imagem SEO default'),
            ]),
            Section::make('Contactos e legal')->columns(3)->schema([
                TextInput::make('primary_email')->email(),
                TextInput::make('phone'),
                TextInput::make('whatsapp'),
                TextInput::make('legal_company_name'),
                TextInput::make('tax_number')->label('NIF'),
                Textarea::make('address')->columnSpanFull(),
                TextInput::make('complaints_book_url')->url()->label('Livro de reclamações'),
                TextInput::make('ral_url')->url()->label('RAL'),
            ]),
            Section::make('Marketing e SEO')->columns(2)->schema([
                TextInput::make('google_analytics_id'),
                TextInput::make('meta_pixel_id'),
                KeyValue::make('social_links')->label('Redes sociais'),
                KeyValue::make('footer_text')->label('Texto footer PT/EN'),
                KeyValue::make('business_hours')->label('Horário'),
                KeyValue::make('seo_defaults')->label('SEO defaults'),
            ]),
        ]);
    }
}
