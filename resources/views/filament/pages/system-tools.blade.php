<x-filament-panels::page>
    <div class="grid gap-6 lg:grid-cols-3">
        <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 lg:col-span-2">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-gray-950 dark:text-white">Laravel Tools</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Run whitelisted maintenance commands from inside the panel.</p>
                </div>
            </div>

            <div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                @foreach($this->tools() as $key => $tool)
                    <button
                        type="button"
                        wire:click="runTool('{{ $key }}')"
                        @if($tool['destructive']) onclick="return confirm('Run {{ $tool['label'] }}? This can change the application state.')" @endif
                        class="rounded-lg border border-gray-200 px-4 py-3 text-left text-sm font-semibold text-gray-800 transition hover:border-amber-400 hover:bg-amber-50 dark:border-gray-700 dark:text-gray-100 dark:hover:border-amber-500 dark:hover:bg-amber-500/10"
                    >
                        {{ $tool['label'] }}
                    </button>
                @endforeach
            </div>
        </section>

        <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h2 class="text-base font-semibold text-gray-950 dark:text-white">Environment Manager</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Switch the active .env file and clear cached configuration.</p>

            <div class="mt-5">
                <span @class([
                    'inline-flex rounded-full px-3 py-1 text-xs font-bold ring-1',
                    'bg-red-50 text-red-700 ring-red-200 dark:bg-red-500/10 dark:text-red-300 dark:ring-red-500/30' => $this->environmentLabel() === 'PRODUCTION',
                    'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:ring-emerald-500/30' => $this->environmentLabel() !== 'PRODUCTION',
                ])>
                    {{ $this->environmentLabel() }}
                </span>
            </div>

            <div class="mt-5 grid gap-3">
                <button
                    type="button"
                    wire:click="switchEnvironment('sandbox')"
                    onclick="return confirm('Switch to SANDBOX by copying .env.local to .env?')"
                    class="rounded-lg border border-emerald-200 px-4 py-3 text-left text-sm font-semibold text-emerald-700 transition hover:bg-emerald-50 dark:border-emerald-500/30 dark:text-emerald-300 dark:hover:bg-emerald-500/10"
                >
                    Switch to SANDBOX
                </button>
                <button
                    type="button"
                    wire:click="switchEnvironment('production')"
                    onclick="return confirm('Switch to PRODUCTION by copying .env.production to .env?')"
                    class="rounded-lg border border-red-200 px-4 py-3 text-left text-sm font-semibold text-red-700 transition hover:bg-red-50 dark:border-red-500/30 dark:text-red-300 dark:hover:bg-red-500/10"
                >
                    Switch to PRODUCTION
                </button>
            </div>
        </section>

        <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <h2 class="text-base font-semibold text-gray-950 dark:text-white">Database Sync</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Copy production MySQL schema and rows into the configured sandbox database.</p>

            <button
                type="button"
                wire:click="syncDatabase"
                onclick="return confirm('This will replace the configured SANDBOX database with production data. Continue?')"
                @disabled($this->environmentLabel() === 'PRODUCTION')
                class="mt-5 w-full rounded-lg border border-red-200 px-4 py-3 text-left text-sm font-semibold text-red-700 transition enabled:hover:bg-red-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-red-500/30 dark:text-red-300 dark:enabled:hover:bg-red-500/10"
            >
                Sync Production Database to Sandbox
            </button>
        </section>

        <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 lg:col-span-2">
            <h2 class="text-base font-semibold text-gray-950 dark:text-white">Execution Log</h2>
            <div class="mt-5 overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-gray-200 text-xs uppercase text-gray-500 dark:border-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="py-2 pr-4">Action</th>
                            <th class="py-2 pr-4">Status</th>
                            <th class="py-2 pr-4">User</th>
                            <th class="py-2 pr-4">Finished</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($this->recentRuns() as $run)
                            <tr>
                                <td class="py-3 pr-4 font-medium text-gray-900 dark:text-gray-100">{{ $run->action }}</td>
                                <td class="py-3 pr-4">
                                    <span @class([
                                        'rounded-full px-2 py-1 text-xs font-semibold',
                                        'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' => $run->status === 'succeeded',
                                        'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-300' => $run->status === 'failed',
                                        'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300' => in_array($run->status, ['pending', 'running'], true),
                                    ])>{{ $run->status }}</span>
                                </td>
                                <td class="py-3 pr-4 text-gray-600 dark:text-gray-400">{{ $run->user?->name ?? 'System' }}</td>
                                <td class="py-3 pr-4 text-gray-600 dark:text-gray-400">{{ $run->finished_at?->diffForHumans() ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-500 dark:text-gray-400">No system tool runs yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-filament-panels::page>
