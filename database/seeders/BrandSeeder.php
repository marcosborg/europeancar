<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\CarModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = ['BMW', 'Mercedes-Benz', 'Audi', 'Volkswagen', 'Peugeot', 'Renault', 'Tesla', 'Volvo', 'Toyota', 'Nissan', 'Kia', 'Hyundai', 'Ford', 'Opel', 'Fiat', 'Dacia', 'Citroën', 'Seat', 'Skoda'];

        foreach ($brands as $index => $name) {
            $brand = Brand::query()->firstOrCreate(['slug' => Str::slug($name)], [
                'name' => $name,
                'is_active' => true,
                'sort_order' => $index,
            ]);

            foreach (['Série 3', 'Classe C', 'A4', 'Golf', 'Model 3'] as $modelName) {
                if ($index < 5) {
                    CarModel::query()->firstOrCreate([
                        'brand_id' => $brand->id,
                        'slug' => Str::slug($modelName),
                    ], [
                        'name' => $modelName,
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
