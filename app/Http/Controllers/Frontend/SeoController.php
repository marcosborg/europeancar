<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PageTranslation;
use App\Models\Vehicle;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $urls = [
            url('/pt'),
            url('/en'),
            route('vehicles.buy.pt', ['locale' => 'pt']),
            route('vehicles.buy.en', ['locale' => 'en']),
            route('vehicles.rent.pt', ['locale' => 'pt']),
            route('vehicles.rent.en', ['locale' => 'en']),
        ];

        PageTranslation::query()->get()->each(fn ($translation) => $urls[] = url('/'.$translation->locale.'/'.$translation->slug));

        Vehicle::query()->with('translations')->published()->get()->each(function (Vehicle $vehicle) use (&$urls): void {
            foreach ($vehicle->translations as $translation) {
                $urls[] = route($translation->locale === 'en' ? 'vehicles.show.en' : 'vehicles.show.pt', [
                    'locale' => $translation->locale,
                    'slug' => $translation->slug,
                ]);
            }
        });

        return response()
            ->view('front.seo.sitemap', ['urls' => array_unique($urls)])
            ->header('Content-Type', 'application/xml');
    }

    public function robots(): Response
    {
        return response("User-agent: *\nAllow: /\nSitemap: ".url('/sitemap.xml')."\n", 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
