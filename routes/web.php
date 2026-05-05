<?php

use App\Http\Controllers\Admin\SocialExportController;
use App\Http\Controllers\Frontend\CookieConsentController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\SeoController;
use App\Http\Controllers\Frontend\VehicleController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/pt');

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
Route::post('/cookies/consent', [CookieConsentController::class, 'store'])->name('cookies.consent');

Route::middleware('auth')->prefix('admin/social')->group(function (): void {
    Route::get('/vehicles.csv', [SocialExportController::class, 'csv'])->name('admin.social-csv');
    Route::get('/{vehicle}/{locale?}/{format?}', [SocialExportController::class, 'preview'])->name('admin.social-preview');
});

Route::prefix('{locale}')
    ->whereIn('locale', ['pt', 'en'])
    ->middleware('locale')
    ->group(function (): void {
        Route::get('/', HomeController::class)->name('home');

        Route::get('/comprar', [VehicleController::class, 'buy'])->name('vehicles.buy.pt');
        Route::get('/buy', [VehicleController::class, 'buy'])->name('vehicles.buy.en');
        Route::get('/alugar', [VehicleController::class, 'rent'])->name('vehicles.rent.pt');
        Route::get('/rent', [VehicleController::class, 'rent'])->name('vehicles.rent.en');

        Route::get('/viaturas/{slug}', [VehicleController::class, 'show'])->name('vehicles.show.pt');
        Route::get('/vehicles/{slug}', [VehicleController::class, 'show'])->name('vehicles.show.en');
        Route::get('/{slug}', [PageController::class, 'show'])->name('pages.show');
    });
