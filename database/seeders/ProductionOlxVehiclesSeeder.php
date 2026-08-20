<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Vehicle;
use App\Models\VehicleFeature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ProductionOlxVehiclesSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->vehicles() as $data) {
            $vehicle = DB::transaction(function () use ($data): Vehicle {
                $brand = Brand::query()->firstOrCreate(
                    ['slug' => $data['brand']['slug']],
                    ['name' => $data['brand']['name'], 'is_active' => true, 'sort_order' => 0],
                );

                $carModel = CarModel::query()
                    ->whereBelongsTo($brand)
                    ->where('name', $data['model']['name'])
                    ->first() ?? CarModel::query()->create([
                        'brand_id' => $brand->id,
                        'name' => $data['model']['name'],
                        'slug' => $data['model']['slug'],
                        'is_active' => true,
                        'sort_order' => 0,
                    ]);

                $vehicle = Vehicle::query()->firstOrCreate(
                    ['sku' => $data['sku']],
                    [...$data['vehicle'], 'brand_id' => $brand->id, 'car_model_id' => $carModel->id],
                );

                foreach ($data['translations'] as $translation) {
                    $vehicle->translations()->firstOrCreate(
                        ['locale' => $translation['locale']],
                        Arr::except($translation, 'locale'),
                    );
                }

                $featureIds = VehicleFeature::query()
                    ->whereIn('slug', $data['features'])
                    ->pluck('id');

                $vehicle->features()->syncWithoutDetaching($featureIds);

                return $vehicle;
            });

            $this->importMedia($vehicle, $data['media']);
        }
    }

    /** @param list<string> $files */
    private function importMedia(Vehicle $vehicle, array $files): void
    {
        $existingMedia = $vehicle->getMedia();
        $hasImportedMedia = $existingMedia->contains(
            fn ($media): bool => filled($media->getCustomProperty('production_import_key')),
        );

        if ($existingMedia->isNotEmpty() && ! $hasImportedMedia) {
            return;
        }

        foreach ($files as $index => $fileName) {
            $importKey = $vehicle->sku.'/'.$fileName;

            if ($vehicle->getMedia()->contains(
                fn ($media): bool => $media->getCustomProperty('production_import_key') === $importKey,
            )) {
                continue;
            }

            $path = database_path('seeders/data/production-vehicles/'.$importKey);

            if (! is_file($path)) {
                throw new RuntimeException("Missing production vehicle media: {$path}");
            }

            $vehicle->addMedia($path)
                ->preservingOriginal()
                ->withCustomProperties(['production_import_key' => $importKey])
                ->usingName($vehicle->publicTitle('pt').' '.($index + 1))
                ->usingFileName($fileName)
                ->toMediaCollection($index === 0 ? 'vehicle_main' : 'vehicle_gallery');
        }
    }

    /** @return list<array<string, mixed>> */
    private function vehicles(): array
    {
        return [
            $this->vehicle(
                sku: 'OLX-672789664',
                model: ['name' => 'Astra', 'slug' => 'astra'],
                attributes: ['version' => '1.4 Turbo Cosmo', 'year' => 2012, 'mileage' => 147000, 'sale_price' => 12800, 'vin' => 'W0LPE5EC2DG007558', 'doors' => 5, 'seats' => 5, 'body_type' => 'Sedan', 'segment' => 'Sedan', 'transmission' => 'Manual', 'engine_capacity' => 1400, 'power_hp' => 140, 'co2_emissions' => 138, 'maintenance_history' => true, 'published_at' => '2026-08-20 13:49:00', 'internal_notes' => 'Importado do anúncio OLX 672789664. Primeira matrícula indicada: Julho de 2012. Fonte: https://www.olx.pt/d/anuncio/opel-astra-1-4-turbo-cosmo-2012-gasolina-IDJvXkc.html'],
                translations: [
                    ['locale' => 'pt', 'title' => 'Opel Astra 1.4 Turbo Cosmo 2012', 'slug' => 'opel-astra-14-turbo-cosmo-2012-672789664', 'description' => 'Opel Astra sedan de 2012, com motor 1.4 Turbo a gasolina de 140 cv e caixa manual. Uma viatura confortável e fiável, indicada para utilização familiar e urbana. Tem 147.000 km, cinco lugares, cinco portas e histórico de manutenção regular.'],
                    ['locale' => 'en', 'title' => '2012 Opel Astra 1.4 Turbo Cosmo', 'slug' => '2012-opel-astra-14-turbo-cosmo-672789664', 'description' => '2012 Opel Astra sedan with a 140 hp 1.4 Turbo petrol engine and manual transmission. A comfortable and reliable car suited to family and urban use. It has 147,000 km, five seats, five doors and a regular maintenance history.'],
                ],
                media: ['01.jpg', '02.jpg', '03.jpg', '04.jpg', '05.jpg', '06.jpg', '07.jpg', '08.jpg', '09.jpg'],
            ),
            $this->vehicle(
                sku: 'OLX-672784946',
                model: ['name' => 'Cascada', 'slug' => 'cascada'],
                attributes: ['version' => '1.4 Turbo', 'year' => 2013, 'mileage' => 89500, 'sale_price' => 14980, 'vin' => 'W0LWT3DCXDG074674', 'doors' => 2, 'seats' => 4, 'body_type' => 'Cabrio', 'segment' => 'Cabrio', 'transmission' => 'Manual', 'engine_capacity' => 1400, 'power_hp' => 140, 'co2_emissions' => 138, 'published_at' => '2026-08-20 13:49:01', 'internal_notes' => 'Importado do anúncio OLX 672784946. Primeira matrícula indicada: Julho de 2013. Fonte: https://www.olx.pt/d/anuncio/2013-opel-cascade-cabrio-1-4-turbo-gasolina-IDJvV66.html'],
                translations: [
                    ['locale' => 'pt', 'title' => 'Opel Cascada Cabrio 1.4 Turbo 2013', 'slug' => 'opel-cascada-cabrio-14-turbo-2013-672784946', 'description' => 'Opel Cascada Cabrio de 2013, com motor 1.4 Turbo a gasolina de 140 cv e caixa manual. Um cabriolet elegante, confortável e pensado para desfrutar da condução ao ar livre. Tem 89.500 km, quatro lugares e duas portas.'],
                    ['locale' => 'en', 'title' => '2013 Opel Cascada Cabrio 1.4 Turbo', 'slug' => '2013-opel-cascada-cabrio-14-turbo-672784946', 'description' => '2013 Opel Cascada Cabrio with a 140 hp 1.4 Turbo petrol engine and manual transmission. An elegant and comfortable convertible designed for open-air driving. It has 89,500 km, four seats and two doors.'],
                ],
                media: ['01.jpg', '02.jpg', '03.jpg', '04.jpg', '05.jpg', '06.jpg', '07.jpg', '08.jpg', '09.jpg', '10.jpg', '11.jpg', '12.jpg'],
            ),
            $this->vehicle(
                sku: 'OLX-672765707',
                model: ['name' => 'Mokka', 'slug' => 'mokka'],
                attributes: ['version' => '1.4 Turbo', 'year' => 2015, 'mileage' => 144456, 'sale_price' => 13500, 'vin' => 'W0LJC7E88G4088347', 'doors' => 5, 'seats' => 5, 'body_type' => 'SUV', 'segment' => 'SUV/TT', 'transmission' => 'Manual', 'engine_capacity' => 1400, 'power_hp' => 140, 'co2_emissions' => 138, 'published_at' => '2026-08-20 13:49:02', 'internal_notes' => 'Importado do anúncio OLX 672765707. Primeira matrícula indicada: Agosto de 2015. Fonte: https://www.olx.pt/d/anuncio/opel-mokka-suv-2015-1-4-gasoline-IDJvR5N.html'],
                translations: [
                    ['locale' => 'pt', 'title' => 'Opel Mokka SUV 1.4 Turbo 2015', 'slug' => 'opel-mokka-suv-14-turbo-2015-672765707', 'description' => 'Opel Mokka SUV de 2015, com motor 1.4 Turbo a gasolina de 140 cv e caixa manual. Um SUV compacto, versátil e confortável para o dia a dia. Tem 144.456 km, cinco lugares, cinco portas, ar condicionado, volante multifunções, computador de bordo, jantes de liga leve e faróis de nevoeiro.'],
                    ['locale' => 'en', 'title' => '2015 Opel Mokka SUV 1.4 Turbo', 'slug' => '2015-opel-mokka-suv-14-turbo-672765707', 'description' => '2015 Opel Mokka SUV with a 140 hp 1.4 Turbo petrol engine and manual transmission. A compact, versatile and comfortable SUV for everyday use. It has 144,456 km, five seats, five doors, air conditioning, a multifunction steering wheel, trip computer, alloy wheels and fog lights.'],
                ],
                media: ['01.jpg', '02.jpg', '03.jpg', '04.jpg', '05.jpg', '06.jpg', '07.jpg', '08.jpg', '09.jpg', '10.jpg', '11.jpg', '12.jpg'],
                features: ['jantes-especiais'],
            ),
            [
                ...$this->vehicle(
                    sku: 'OLX-672734430',
                    model: ['name' => 'RS 5', 'slug' => 'rs-5'],
                    attributes: ['version' => '4.2 V8 580 HP', 'year' => 2014, 'mileage' => 171200, 'sale_price' => 60000, 'vin' => 'WUAZZZ8T5EA902390', 'doors' => 2, 'seats' => 4, 'body_type' => 'Coupé', 'segment' => 'Coupé', 'transmission' => 'Automática', 'engine_capacity' => 4200, 'power_hp' => 580, 'co2_emissions' => 252, 'published_at' => '2026-08-20 13:49:03', 'internal_notes' => 'Importado do anúncio OLX 672734430. Primeira matrícula indicada: Agosto de 2014. Fonte: https://www.olx.pt/d/anuncio/audi-rs-5-580hp-IDJvIXk.html'],
                    translations: [
                        ['locale' => 'pt', 'title' => 'Audi RS 5 4.2 V8 580 cv 2014', 'slug' => 'audi-rs-5-42-v8-580-cv-2014-672734430', 'description' => 'Audi RS 5 Coupé de 2014, com motor 4.2 V8 a gasolina de 580 cv e caixa automática. Um desportivo de elevado desempenho que combina potência, sofisticação e design elegante. Tem 171.200 km, quatro lugares e duas portas.'],
                        ['locale' => 'en', 'title' => '2014 Audi RS 5 4.2 V8 580 hp', 'slug' => '2014-audi-rs-5-42-v8-580-hp-672734430', 'description' => '2014 Audi RS 5 Coupé with a 580 hp 4.2 V8 petrol engine and automatic transmission. A high-performance sports car combining power, sophistication and elegant design. It has 171,200 km, four seats and two doors.'],
                    ],
                    media: ['01.jpg', '02.jpg', '03.jpg', '04.jpg'],
                ),
                'brand' => ['name' => 'Audi', 'slug' => 'audi'],
            ],
        ];
    }

    /** @param array<string, mixed> $attributes @param list<array<string, string>> $translations @param list<string> $media @param list<string> $features */
    private function vehicle(string $sku, array $model, array $attributes, array $translations, array $media, array $features = []): array
    {
        return [
            'sku' => $sku,
            'brand' => ['name' => 'Opel', 'slug' => 'opel'],
            'model' => $model,
            'vehicle' => [
                'sku' => $sku,
                'status' => 'published',
                'type' => 'sale',
                'origin_country' => 'Importado',
                'current_location' => 'Moncarapacho e Fuseta, Faro',
                'fuel_type' => 'Gasolina',
                'rental_availability' => null,
                ...$attributes,
            ],
            'translations' => array_map(fn (array $translation): array => [
                ...$translation,
                'meta_title' => $translation['title'].' | European Car Sales and Rentals',
                'meta_description' => mb_substr($translation['description'], 0, 160),
            ], $translations),
            'features' => $features,
            'media' => $media,
        ];
    }
}
