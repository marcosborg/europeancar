<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SystemTools\EnvironmentSwitcher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SystemEnvironmentController extends Controller
{
    public function __invoke(Request $request, string $environment, EnvironmentSwitcher $switcher): RedirectResponse
    {
        abort_unless($request->user()?->can('system_tools'), 403);

        $run = $switcher->switchTo($environment, $request->user()?->id);

        if ($run->status !== 'succeeded') {
            return back()->withErrors([
                'environment' => $run->error ?: 'Environment switch failed.',
            ]);
        }

        $request->session()->regenerateToken();

        return redirect()->route('filament.admin.pages.system-tools');
    }
}
