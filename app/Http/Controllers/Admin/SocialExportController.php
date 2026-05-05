<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\Vehicle;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class SocialExportController extends Controller
{
    public function preview(Vehicle $vehicle, string $locale = 'pt', string $format = 'square'): View
    {
        $vehicle->load(['brand', 'carModel', 'translations', 'media']);

        return view('admin.social.preview', [
            'vehicle' => $vehicle,
            'locale' => in_array($locale, ['pt', 'en'], true) ? $locale : 'pt',
            'format' => $format === 'story' ? 'story' : 'square',
            'settings' => SiteSetting::current(),
            'copy' => $this->copy($vehicle, $locale),
        ]);
    }

    public function csv(): StreamedResponse
    {
        return response()->streamDownload(function (): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['title', 'description', 'price', 'url', 'image', 'brand', 'model', 'year', 'km', 'fuel', 'transmission']);

            Vehicle::query()->with(['brand', 'carModel', 'translations', 'media'])->published()->each(function (Vehicle $vehicle) use ($handle): void {
                $translation = $vehicle->translation('pt');
                fputcsv($handle, [
                    $translation?->title,
                    strip_tags((string) $translation?->description),
                    $vehicle->sale_price,
                    $vehicle->publicUrl('pt'),
                    $vehicle->mainImageUrl(),
                    $vehicle->brand?->name,
                    $vehicle->carModel?->name,
                    $vehicle->year,
                    $vehicle->mileage,
                    $vehicle->fuel_type,
                    $vehicle->transmission,
                ]);
            });
        }, 'european-car-vehicles.csv');
    }

    private function copy(Vehicle $vehicle, string $locale): string
    {
        $title = $vehicle->publicTitle($locale);
        $price = $vehicle->price_on_request ? ($locale === 'en' ? 'Price on request' : 'Preço sob consulta') : number_format((float) $vehicle->sale_price, 0, ',', ' ').'€';
        $cta = $locale === 'en' ? 'Talk to us and discover this European opportunity.' : 'Fale connosco e descubra esta oportunidade europeia.';

        return "{$title} disponível para entrega.\n{$vehicle->transmission}, {$vehicle->fuel_type}, {$vehicle->mileage} km, {$price}.\n{$cta}\n#EuropeanCar #DriveEurope #ChooseExcellence";
    }
}
