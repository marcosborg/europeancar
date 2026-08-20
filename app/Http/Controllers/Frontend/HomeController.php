<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\SiteSetting;
use App\Models\Vehicle;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(string $locale): View
    {
        $page = Page::query()
            ->with('translations')
            ->where('template', 'home')
            ->published()
            ->first();

        $featuredVehicles = Vehicle::query()
            ->with(['brand', 'carModel', 'translations', 'media'])
            ->published()
            ->orderByDesc('featured')
            ->orderBy('featured_order')
            ->latest('published_at')
            ->latest('id')
            ->limit(6)
            ->get();

        return view('front.home', [
            'locale' => $locale,
            'page' => $page,
            'translation' => $page?->translation($locale),
            'settings' => SiteSetting::current(),
            'featuredVehicles' => $featuredVehicles,
        ]);
    }
}
