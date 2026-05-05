<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageTranslation;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(string $locale, string $slug): View
    {
        $translation = PageTranslation::query()
            ->where('locale', $locale)
            ->where('slug', $slug)
            ->firstOrFail();

        $page = Page::query()
            ->with(['translations', 'media'])
            ->published()
            ->findOrFail($translation->page_id);

        return view('front.pages.show', compact('locale', 'page', 'translation'));
    }
}
