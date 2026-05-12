<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use App\Models\Brand;
use App\Models\CarModel;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Viatura')->tabs([
                    Tab::make('Publicação')->schema([
                        Grid::make(4)->schema([
                            TextInput::make('sku')->label('Referência / SKU')->required()->unique(ignoreRecord: true),
                            Select::make('status')->required()->options([
                                'draft' => 'Rascunho',
                                'published' => 'Publicado',
                                'reserved' => 'Reservado',
                                'sold' => 'Vendido',
                                'rented' => 'Alugado',
                                'unavailable' => 'Indisponível',
                            ])->default('draft'),
                            Select::make('type')->required()->options([
                                'sale' => 'Venda',
                                'rent' => 'Aluguer',
                                'sale_rent' => 'Venda e aluguer',
                            ])->default('sale'),
                            DatePicker::make('published_at')->label('Publicado em'),
                            Toggle::make('featured')->label('Destaque homepage'),
                            Toggle::make('premium')->label('Viatura premium'),
                            TextInput::make('featured_order')->numeric()->default(0),
                            Textarea::make('internal_notes')->label('Observações internas')->columnSpanFull(),
                        ]),
                    ]),
                    Tab::make('Identificação')->schema([
                        Grid::make(3)->schema([
                            Select::make('brand_id')
                                ->label('Marca')
                                ->relationship('brand', 'name')
                                ->searchable()
                                ->preload()
                                ->live()
                                ->required()
                                ->afterStateUpdated(fn (Set $set) => $set('car_model_id', null))
                                ->createOptionForm([
                                    TextInput::make('name')
                                        ->label('Nome')
                                        ->required()
                                        ->maxLength(255)
                                        ->unique(Brand::class, 'name')
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),
                                    TextInput::make('slug')
                                        ->required()
                                        ->maxLength(255)
                                        ->unique(Brand::class, 'slug'),
                                    TextInput::make('sort_order')
                                        ->label('Ordem')
                                        ->numeric()
                                        ->default(0),
                                    Toggle::make('is_active')
                                        ->label('Ativa')
                                        ->default(true),
                                ]),
                            Select::make('car_model_id')
                                ->label('Modelo')
                                ->options(fn (Get $get): array => CarModel::query()
                                    ->where('brand_id', $get('brand_id'))
                                    ->where('is_active', true)
                                    ->orderBy('sort_order')
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                                    ->all())
                                ->searchable()
                                ->preload()
                                ->disabled(fn (Get $get): bool => blank($get('brand_id')))
                                ->createOptionForm([
                                    TextInput::make('name')
                                        ->label('Nome')
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),
                                    TextInput::make('slug')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('sort_order')
                                        ->label('Ordem')
                                        ->numeric()
                                        ->default(0),
                                    Toggle::make('is_active')
                                        ->label('Ativo')
                                        ->default(true),
                                ])
                                ->createOptionUsing(function (array $data, Get $get): int {
                                    return CarModel::query()->create([
                                        'brand_id' => $get('brand_id'),
                                        'name' => $data['name'],
                                        'slug' => self::uniqueCarModelSlug((int) $get('brand_id'), $data['slug']),
                                        'sort_order' => $data['sort_order'] ?? 0,
                                        'is_active' => $data['is_active'] ?? true,
                                    ])->getKey();
                                }),
                            TextInput::make('version')->label('Versão'),
                            TextInput::make('year')->numeric()->minValue(1900)->maxValue((int) date('Y') + 1),
                            DatePicker::make('first_registration_date')->label('Primeira matrícula'),
                            TextInput::make('mileage')->label('Quilómetros')->numeric(),
                            TextInput::make('origin_country')->label('País de origem'),
                            TextInput::make('current_location')->label('Localização atual'),
                            TextInput::make('license_plate')->label('Matrícula privada'),
                            TextInput::make('vin')->label('VIN privado/parcial'),
                        ]),
                    ]),
                    Tab::make('Conteúdo PT/EN')->schema([
                        Repeater::make('translations')->relationship()->columns(2)->schema([
                            Select::make('locale')->options(['pt' => 'Português', 'en' => 'English'])->required(),
                            TextInput::make('title')->required()->maxLength(255)->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),
                            TextInput::make('slug')->required()->maxLength(255),
                            TextInput::make('meta_title')->maxLength(255),
                            Textarea::make('meta_description')->rows(3),
                            RichEditor::make('description')->columnSpanFull(),
                        ])->defaultItems(2)->columnSpanFull(),
                    ]),
                    Tab::make('Venda e financiamento')->schema([
                        Grid::make(4)->schema([
                            TextInput::make('sale_price')->label('Preço venda')->numeric()->prefix('€'),
                            TextInput::make('old_price')->label('Preço antigo')->numeric()->prefix('€'),
                            Toggle::make('price_on_request')->label('Preço sob consulta'),
                            Toggle::make('financing_available')->label('Financiamento'),
                            TextInput::make('estimated_monthly_payment')->label('Mensalidade estimada')->numeric()->prefix('€'),
                            Toggle::make('trade_in_available')->label('Retoma'),
                            Toggle::make('vat_deductible')->label('IVA dedutível'),
                            TextInput::make('warranty_months')->label('Garantia meses')->numeric(),
                        ]),
                    ]),
                    Tab::make('Aluguer')->schema([
                        Grid::make(4)->schema([
                            TextInput::make('daily_price')->label('Preço diário')->numeric()->prefix('€'),
                            TextInput::make('weekly_price')->label('Preço semanal')->numeric()->prefix('€'),
                            TextInput::make('monthly_price')->label('Preço mensal')->numeric()->prefix('€'),
                            TextInput::make('deposit')->label('Caução')->numeric()->prefix('€'),
                            TextInput::make('included_km_per_day')->label('Km incluídos/dia')->numeric(),
                            TextInput::make('extra_km_price')->label('Preço km extra')->numeric()->prefix('€'),
                            TextInput::make('minimum_driver_age')->label('Idade mínima')->numeric(),
                            TextInput::make('fuel_policy')->label('Política combustível'),
                            Toggle::make('delivery_collection_available')->label('Entrega/recolha'),
                            Select::make('rental_availability')->options([
                                'available' => 'Disponível',
                                'limited' => 'Limitado',
                                'unavailable' => 'Indisponível',
                            ]),
                            Textarea::make('rental_conditions')->label('Condições especiais')->columnSpanFull(),
                        ]),
                    ]),
                    Tab::make('Dados técnicos')->schema([
                        Grid::make(4)->schema([
                            TextInput::make('doors')->numeric(),
                            TextInput::make('seats')->numeric(),
                            TextInput::make('exterior_color')->label('Cor exterior'),
                            TextInput::make('interior_color')->label('Cor interior'),
                            TextInput::make('paint_type')->label('Tipo pintura'),
                            TextInput::make('body_type')->label('Carroçaria'),
                            TextInput::make('segment')->label('Segmento'),
                            Select::make('fuel_type')->options(['Gasolina' => 'Gasolina', 'Diesel' => 'Diesel', 'Híbrido' => 'Híbrido', 'Híbrido Plug-in' => 'Híbrido Plug-in', 'Elétrico' => 'Elétrico']),
                            Select::make('transmission')->options(['Manual' => 'Manual', 'Automática' => 'Automática']),
                            TextInput::make('drivetrain')->label('Tração'),
                            TextInput::make('engine_capacity')->label('Cilindrada')->numeric(),
                            TextInput::make('power_hp')->label('Potência CV')->numeric(),
                            TextInput::make('power_kw')->label('Potência kW')->numeric(),
                            TextInput::make('torque_nm')->label('Binário Nm')->numeric(),
                            TextInput::make('euro_standard')->label('Norma Euro'),
                            TextInput::make('co2_emissions')->label('CO2')->numeric(),
                            TextInput::make('combined_consumption')->label('Consumo combinado')->numeric(),
                            TextInput::make('electric_range')->label('Autonomia elétrica')->numeric(),
                            TextInput::make('battery_capacity_kwh')->label('Bateria kWh')->numeric(),
                            TextInput::make('charging_ac')->label('Carregamento AC'),
                            TextInput::make('charging_dc')->label('Carregamento DC'),
                            Toggle::make('maintenance_history')->label('Histórico manutenção'),
                            Toggle::make('service_book')->label('Livro revisões'),
                            DatePicker::make('inspection_valid_until')->label('Inspeção válida até'),
                            TextInput::make('previous_owners')->label('N.º proprietários')->numeric(),
                            Toggle::make('non_smoker')->label('Não fumador'),
                            Toggle::make('accident_free')->label('Sem acidentes'),
                        ]),
                    ]),
                    Tab::make('Equipamento')->schema([
                        Select::make('features')->relationship('features', 'slug')->multiple()->preload()->searchable()->columnSpanFull(),
                    ]),
                    Tab::make('Fotos e documentos')->schema([
                        Section::make('Media')->columns(2)->schema([
                            SpatieMediaLibraryFileUpload::make('vehicle_main')->collection('vehicle_main')->image()->label('Imagem principal'),
                            SpatieMediaLibraryFileUpload::make('vehicle_gallery')->collection('vehicle_gallery')->image()->multiple()->reorderable()->label('Galeria'),
                            SpatieMediaLibraryFileUpload::make('vehicle_social')->collection('vehicle_social')->image()->multiple()->label('Imagens redes sociais'),
                            SpatieMediaLibraryFileUpload::make('vehicle_documents')->collection('vehicle_documents')->multiple()->label('Documentos internos'),
                            SpatieMediaLibraryFileUpload::make('vehicle_technical_sheet')->collection('vehicle_technical_sheet')->label('Ficha técnica PDF')->acceptedFileTypes(['application/pdf']),
                        ]),
                    ]),
                ])->columnSpanFull(),
            ]);
    }

    private static function uniqueCarModelSlug(int $brandId, string $slug): string
    {
        $slug = Str::slug($slug);
        $baseSlug = $slug;
        $counter = 2;

        while (CarModel::query()
            ->where('brand_id', $brandId)
            ->where('slug', $slug)
            ->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
