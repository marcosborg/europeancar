<x-filament-panels::page>
    <div style="display: grid; gap: 1.5rem;">
        <div style="display: grid; gap: 1.5rem; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
            <x-filament::section
                heading="Laravel Tools"
                description="Run whitelisted maintenance commands from inside the panel."
            >
                <div style="display: grid; gap: .75rem; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
                    @foreach($this->tools() as $key => $tool)
                        @if($tool['destructive'])
                            <div x-data="{ open: false }">
                                <x-filament::button
                                    type="button"
                                    color="danger"
                                    x-on:click="open = true"
                                    style="width: 100%;"
                                >
                                    {{ $tool['label'] }}
                                </x-filament::button>

                                <div
                                    x-cloak
                                    x-show="open"
                                    x-transition.opacity
                                    style="position: fixed; inset: 0; z-index: 50; display: grid; place-items: center; background: rgb(15 23 42 / .55); padding: 1rem;"
                                >
                                    <div
                                        x-on:click.outside="open = false"
                                        style="width: min(100%, 30rem); border-radius: .75rem; background: white; color: #111827; box-shadow: 0 24px 80px rgb(15 23 42 / .35);"
                                    >
                                        <div style="padding: 1.25rem;">
                                            <h2 style="margin: 0; font-size: 1rem; font-weight: 700;">Run {{ $tool['label'] }}?</h2>
                                            <p style="margin: .5rem 0 0; color: #6b7280; font-size: .875rem;">
                                                This command can change application state and will be logged.
                                            </p>
                                        </div>

                                        <div style="display: flex; justify-content: flex-end; gap: .75rem; border-top: 1px solid #e5e7eb; padding: 1rem 1.25rem;">
                                            <button
                                                type="button"
                                                x-on:click="open = false"
                                                style="border: 1px solid #d1d5db; border-radius: .5rem; padding: .625rem .875rem; color: #374151; font-weight: 600;"
                                            >
                                                Cancel
                                            </button>

                                            <button
                                                type="button"
                                                x-on:click="open = false; $wire.runTool('{{ $key }}')"
                                                style="border-radius: .5rem; padding: .625rem .875rem; background: #dc2626; color: white; font-weight: 700;"
                                            >
                                                Run command
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <x-filament::button
                                type="button"
                                color="primary"
                                outlined
                                wire:click="runTool('{{ $key }}')"
                            >
                                {{ $tool['label'] }}
                            </x-filament::button>
                        @endif
                    @endforeach
                </div>
            </x-filament::section>

            <x-filament::section
                heading="Environment Manager"
                description="Switch the active .env file and clear cached configuration."
            >
                <div
                    x-data="{ confirming: null }"
                    style="display: grid; gap: 1rem;"
                >
                    <div>
                        <x-filament::badge :color="$this->environmentLabel() === 'PRODUCTION' ? 'danger' : 'success'">
                            {{ $this->environmentLabel() }}
                        </x-filament::badge>
                    </div>

                    <div style="display: grid; gap: .75rem;">
                        <form
                            x-ref="sandboxForm"
                            method="POST"
                            action="{{ route('admin.system-tools.environment', ['environment' => 'sandbox']) }}"
                        >
                            @csrf

                            <button
                                type="button"
                                x-on:click="confirming = 'sandbox'"
                                style="width: 100%; border: 1px solid #22c55e; border-radius: .5rem; padding: .625rem .875rem; color: #16a34a; font-weight: 600;"
                            >
                                Switch to SANDBOX
                            </button>
                        </form>

                        <form
                            x-ref="productionForm"
                            method="POST"
                            action="{{ route('admin.system-tools.environment', ['environment' => 'production']) }}"
                        >
                            @csrf

                            <button
                                type="button"
                                x-on:click="confirming = 'production'"
                                style="width: 100%; border: 1px solid #ef4444; border-radius: .5rem; padding: .625rem .875rem; color: #dc2626; font-weight: 600;"
                            >
                                Switch to PRODUCTION
                            </button>
                        </form>
                    </div>

                    <div
                        x-cloak
                        x-show="confirming"
                        x-transition.opacity
                        style="position: fixed; inset: 0; z-index: 50; display: grid; place-items: center; background: rgb(15 23 42 / .55); padding: 1rem;"
                    >
                        <div
                            x-on:click.outside="confirming = null"
                            style="width: min(100%, 30rem); border-radius: .75rem; background: white; color: #111827; box-shadow: 0 24px 80px rgb(15 23 42 / .35);"
                        >
                            <div style="padding: 1.25rem 1.25rem 0;">
                                <div style="display: flex; align-items: center; gap: .75rem;">
                                    <div
                                        x-bind:style="confirming === 'production' ? 'display:grid;place-items:center;width:2.5rem;height:2.5rem;border-radius:999px;background:#fee2e2;color:#dc2626;' : 'display:grid;place-items:center;width:2.5rem;height:2.5rem;border-radius:999px;background:#dcfce7;color:#16a34a;'"
                                    >
                                        !
                                    </div>

                                    <div>
                                        <h2 style="margin: 0; font-size: 1rem; font-weight: 700;">
                                            <span x-show="confirming === 'production'">Switch to PRODUCTION?</span>
                                            <span x-show="confirming === 'sandbox'">Switch to SANDBOX?</span>
                                        </h2>
                                        <p style="margin: .25rem 0 0; color: #6b7280; font-size: .875rem;">
                                            The active environment file will be copied to <code>.env</code> and cached configuration will be cleared.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div style="padding: 1rem 1.25rem;">
                                <p
                                    x-show="confirming === 'production'"
                                    style="margin: 0; border-radius: .5rem; background: #fef2f2; color: #991b1b; padding: .75rem; font-size: .875rem;"
                                >
                                    You are switching the panel to production credentials. Confirm only if you intend to work with live data.
                                </p>

                                <p
                                    x-show="confirming === 'sandbox'"
                                    style="margin: 0; border-radius: .5rem; background: #f0fdf4; color: #166534; padding: .75rem; font-size: .875rem;"
                                >
                                    You are switching back to the local sandbox configuration.
                                </p>
                            </div>

                            <div style="display: flex; justify-content: flex-end; gap: .75rem; border-top: 1px solid #e5e7eb; padding: 1rem 1.25rem;">
                                <button
                                    type="button"
                                    x-on:click="confirming = null"
                                    style="border: 1px solid #d1d5db; border-radius: .5rem; padding: .625rem .875rem; color: #374151; font-weight: 600;"
                                >
                                    Cancel
                                </button>

                                <button
                                    type="button"
                                    x-on:click="confirming === 'production' ? $refs.productionForm.requestSubmit() : $refs.sandboxForm.requestSubmit()"
                                    x-bind:style="confirming === 'production' ? 'border-radius:.5rem;padding:.625rem .875rem;background:#dc2626;color:white;font-weight:700;' : 'border-radius:.5rem;padding:.625rem .875rem;background:#16a34a;color:white;font-weight:700;'"
                                >
                                    Confirm switch
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </x-filament::section>

            <x-filament::section
                heading="Database Sync"
                description="Copy production MySQL schema and rows into the configured sandbox database."
            >
                <div x-data="{ open: false }">
                    <x-filament::button
                        type="button"
                        color="danger"
                        :disabled="$this->environmentLabel() === 'PRODUCTION'"
                        x-on:click="open = true"
                    >
                        Sync Production Database to Sandbox
                    </x-filament::button>

                    <div
                        x-cloak
                        x-show="open"
                        x-transition.opacity
                        style="position: fixed; inset: 0; z-index: 50; display: grid; place-items: center; background: rgb(15 23 42 / .55); padding: 1rem;"
                    >
                        <div
                            x-on:click.outside="open = false"
                            style="width: min(100%, 32rem); border-radius: .75rem; background: white; color: #111827; box-shadow: 0 24px 80px rgb(15 23 42 / .35);"
                        >
                            <div style="padding: 1.25rem;">
                                <h2 style="margin: 0; font-size: 1rem; font-weight: 700;">Sync production database?</h2>
                                <p style="margin: .5rem 0 0; color: #6b7280; font-size: .875rem;">
                                    This will replace the configured sandbox database with production schema and rows.
                                </p>
                            </div>

                            <div style="padding: 0 1.25rem 1rem;">
                                <p style="margin: 0; border-radius: .5rem; background: #fef2f2; color: #991b1b; padding: .75rem; font-size: .875rem;">
                                    This action is destructive for sandbox data and is blocked while the panel is in PRODUCTION.
                                </p>
                            </div>

                            <div style="display: flex; justify-content: flex-end; gap: .75rem; border-top: 1px solid #e5e7eb; padding: 1rem 1.25rem;">
                                <button
                                    type="button"
                                    x-on:click="open = false"
                                    style="border: 1px solid #d1d5db; border-radius: .5rem; padding: .625rem .875rem; color: #374151; font-weight: 600;"
                                >
                                    Cancel
                                </button>

                                <button
                                    type="button"
                                    x-on:click="open = false; $wire.syncDatabase()"
                                    style="border-radius: .5rem; padding: .625rem .875rem; background: #dc2626; color: white; font-weight: 700;"
                                >
                                    Start sync
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </x-filament::section>
        </div>

        <x-filament::section heading="Execution Log">
            <div wire:poll.visible.5s style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: .875rem;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--gray-200, #e5e7eb); text-align: left;">
                            <th style="padding: .625rem .75rem .625rem 0;">Action</th>
                            <th style="padding: .625rem .75rem;">Status</th>
                            <th style="padding: .625rem .75rem;">User</th>
                            <th style="padding: .625rem 0 .625rem .75rem;">Finished</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->recentRuns() as $run)
                            <tr style="border-bottom: 1px solid var(--gray-100, #f3f4f6);">
                                <td style="padding: .75rem .75rem .75rem 0; font-weight: 600;">{{ $run->action }}</td>
                                <td style="padding: .75rem;">
                                    <x-filament::badge
                                        :color="match ($run->status) {
                                            'succeeded' => 'success',
                                            'failed' => 'danger',
                                            default => 'warning',
                                        }"
                                    >
                                        {{ $run->status }}
                                    </x-filament::badge>
                                </td>
                                <td style="padding: .75rem;">{{ $run->user?->name ?? 'System' }}</td>
                                <td style="padding: .75rem 0 .75rem .75rem;">{{ $run->finished_at?->diffForHumans() ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding: 1.5rem; text-align: center;">No system tool runs yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
