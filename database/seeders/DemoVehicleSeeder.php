<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoVehicleSeeder extends Seeder
{
    public function run(): void
    {
        $brand = Brand::query()->where('name', 'BMW')->first();
        $model = $brand?->models()->first();

        if (! $brand || ! $model) {
            return;
        }

        $vehicle = Vehicle::query()->firstOrCreate(['sku' => 'ECS-0001'], [
            'brand_id' => $brand->id,
            'car_model_id' => $model->id,
            'status' => 'published',
            'type' => 'sale_rent',
            'version' => 'Touring',
            'year' => 2021,
            'mileage' => 68000,
            'origin_country' => 'Germany',
            'current_location' => 'Portugal',
            'sale_price' => 32900,
            'financing_available' => true,
            'warranty_months' => 18,
            'fuel_type' => 'Diesel',
            'transmission' => 'Automática',
            'body_type' => 'Carrinha',
            'power_hp' => 190,
            'featured' => true,
            'premium' => true,
            'rental_availability' => 'available',
            'daily_price' => 95,
            'published_at' => now(),
        ]);

        foreach (['pt' => 'BMW Série 3 Touring 2021', 'en' => 'BMW 3 Series Touring 2021'] as $locale => $title) {
            $vehicle->translations()->updateOrCreate(['locale' => $locale], [
                'title' => $title,
                'slug' => Str::slug($title),
                'description' => '<p>Viatura europeia selecionada, com garantia disponível e possibilidade de financiamento.</p>',
                'meta_title' => $title,
                'meta_description' => 'Viatura europeia disponível na European Car Sales and Rentals.',
            ]);
        }
    }
}
