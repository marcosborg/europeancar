<?php

namespace Database\Seeders;

use App\Models\VehicleFeature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VehicleFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Segurança' => ['ABS', 'ESP', 'Airbags', 'Isofix'],
            'Conforto' => ['Cruise control', 'Bancos aquecidos', 'Bancos ventilados', 'Ar condicionado automático', 'Climatização bi-zona'],
            'Tecnologia' => ['Apple CarPlay', 'Android Auto', 'Bluetooth', 'GPS', 'Ecrã tátil', 'Painel digital', 'Head-up display', 'Carregamento wireless'],
            'Assistência à condução' => ['Cruise control adaptativo', 'Lane assist', 'Blind spot assist', 'Câmara traseira', 'Câmara 360', 'Sensores de estacionamento'],
            'Exterior' => ['Teto panorâmico', 'Jantes especiais', 'Faróis LED', 'Faróis Matrix LED', 'Portão elétrico'],
            'Interior' => ['Bancos elétricos', 'Estofos em pele', 'Keyless'],
        ];

        foreach ($items as $category => $features) {
            foreach ($features as $feature) {
                $record = VehicleFeature::query()->firstOrCreate(['slug' => Str::slug($feature)], [
                    'category' => $category,
                    'is_active' => true,
                ]);

                $record->translations()->updateOrCreate(['locale' => 'pt'], ['name' => $feature]);
                $record->translations()->updateOrCreate(['locale' => 'en'], ['name' => $feature]);
            }
        }
    }
}
