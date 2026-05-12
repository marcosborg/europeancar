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
                            <x-filament::button
                                type="button"
                                color="danger"
                                wire:click="runTool('{{ $key }}')"
                                onclick="return confirm('Run {{ $tool['label'] }}? This can change the application state.')"
                            >
                                {{ $tool['label'] }}
                            </x-filament::button>
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
                <div style="display: grid; gap: 1rem;">
                    <div>
                        <x-filament::badge :color="$this->environmentLabel() === 'PRODUCTION' ? 'danger' : 'success'">
                            {{ $this->environmentLabel() }}
                        </x-filament::badge>
                    </div>

                    <div style="display: grid; gap: .75rem;">
                        <x-filament::button
                            type="button"
                            color="success"
                            outlined
                            wire:click="switchEnvironment('sandbox')"
                            onclick="return confirm('Switch to SANDBOX by copying .env.local to .env?')"
                        >
                            Switch to SANDBOX
                        </x-filament::button>

                        <x-filament::button
                            type="button"
                            color="danger"
                            outlined
                            wire:click="switchEnvironment('production')"
                            onclick="return confirm('Switch to PRODUCTION by copying .env.production to .env?')"
                        >
                            Switch to PRODUCTION
                        </x-filament::button>
                    </div>
                </div>
            </x-filament::section>

            <x-filament::section
                heading="Database Sync"
                description="Copy production MySQL schema and rows into the configured sandbox database."
            >
                <x-filament::button
                    type="button"
                    color="danger"
                    :disabled="$this->environmentLabel() === 'PRODUCTION'"
                    wire:click="syncDatabase"
                    onclick="return confirm('This will replace the configured SANDBOX database with production data. Continue?')"
                >
                    Sync Production Database to Sandbox
                </x-filament::button>
            </x-filament::section>
        </div>

        <x-filament::section heading="Execution Log">
            <div style="overflow-x: auto;">
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
