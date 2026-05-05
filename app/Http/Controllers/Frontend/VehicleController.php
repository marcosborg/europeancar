<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleTranslation;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function buy(string $locale): View
    {
        return view('front.vehicles.index', [
            'locale' => $locale,
            'mode' => 'sale',
        ]);
    }

    public function rent(string $locale): View
    {
        return view('front.vehicles.index', [
            'locale' => $locale,
            'mode' => 'rent',
        ]);
    }

    public function show(string $locale, string $slug): View
    {
        $translation = VehicleTranslation::query()
            ->where('locale', $locale)
            ->where('slug', $slug)
            ->firstOrFail();

        $vehicle = Vehicle::query()
            ->with(['brand', 'carModel', 'translations', 'features.translations', 'media'])
            ->published()
            ->findOrFail($translation->vehicle_id);

        $similarVehicles = Vehicle::query()
            ->with(['brand', 'carModel', 'translations', 'media'])
            ->published()
            ->whereKeyNot($vehicle->id)
            ->where('brand_id', $vehicle->brand_id)
            ->limit(3)
            ->get();

        return view('front.vehicles.show', compact('locale', 'vehicle', 'translation', 'similarVehicles'));
    }
}
