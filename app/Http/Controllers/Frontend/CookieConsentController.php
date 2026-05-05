<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CookieConsent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CookieConsentController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'locale' => ['required', 'in:pt,en'],
            'analytics' => ['required', 'boolean'],
            'marketing' => ['required', 'boolean'],
        ]);

        $token = $request->cookie('cookie_consent') ?: (string) Str::uuid();

        CookieConsent::query()->updateOrCreate([
            'consent_token' => $token,
        ], [
            'locale' => $data['locale'],
            'necessary' => true,
            'analytics' => $data['analytics'],
            'marketing' => $data['marketing'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'consented_at' => now(),
        ]);

        return response()->json(['ok' => true])->cookie('cookie_consent', $token, 60 * 24 * 365);
    }
}
