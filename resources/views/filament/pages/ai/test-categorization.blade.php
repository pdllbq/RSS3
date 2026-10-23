<x-filament-panels::page>
    <form wire:submit="runAiTest" class="space-y-6">
        {{ $this->form }}

        <div class="flex items-center gap-3">
            <x-filament::button
                type="submit"
                size="lg"
                icon="heroicon-m-play"
                wire:target="runAiTest"
            >
                Run Benchmark Test
            </x-filament::button>

            @if ($testResults)
                <x-filament::button
                    type="button"
                    color="gray"
                    size="lg"
                    wire:click="clearTestResults"
                >
                    Clear Results
                </x-filament::button>
            @endif
        </div>
    </form>

    @if ($testResults)
        <div class="space-y-6">
            {{-- KPI Stats --}}
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                <div class="fi-wi-stats-overview-stat">
                    <div class="fi-wi-stats-overview-stat-content">
                        <div class="fi-wi-stats-overview-stat-label-ctn">
                            <span class="fi-wi-stats-overview-stat-label">Accuracy</span>
                        </div>
                        <div class="fi-wi-stats-overview-stat-value">
                            {{ $testResults['accuracy'] }}%
                        </div>
                        <div class="fi-wi-stats-overview-stat-description">
                            <x-filament::badge :color="$testResults['accuracy'] >= 70 ? 'success' : ($testResults['accuracy'] >= 40 ? 'warning' : 'danger')" size="sm">
                                {{ $testResults['matched'] }} / {{ $testResults['total'] }} matched
                            </x-filament::badge>
                        </div>
                    </div>
                </div>

                <div class="fi-wi-stats-overview-stat">
                    <div class="fi-wi-stats-overview-stat-content">
                        <div class="fi-wi-stats-overview-stat-label-ctn">
                            <span class="fi-wi-stats-overview-stat-label">Total Tested</span>
                        </div>
                        <div class="fi-wi-stats-overview-stat-value">
                            {{ $testResults['total'] }}
                        </div>
                        <div class="fi-wi-stats-overview-stat-description">
                            Articles evaluated
                        </div>
                    </div>
                </div>

                <div class="fi-wi-stats-overview-stat">
                    <div class="fi-wi-stats-overview-stat-content">
                        <div class="fi-wi-stats-overview-stat-label-ctn">
                            <span class="fi-wi-stats-overview-stat-label">Matches</span>
                        </div>
                        <div class="fi-wi-stats-overview-stat-value">
                            {{ $testResults['matched'] }}
                        </div>
                        <div class="fi-wi-stats-overview-stat-description">
                            <x-filament::badge color="success" size="sm">Matches Ground Truth</x-filament::badge>
                        </div>
                    </div>
                </div>

                <div class="fi-wi-stats-overview-stat">
                    <div class="fi-wi-stats-overview-stat-content">
                        <div class="fi-wi-stats-overview-stat-label-ctn">
                            <span class="fi-wi-stats-overview-stat-label">Mismatches</span>
                        </div>
                        <div class="fi-wi-stats-overview-stat-value">
                            {{ $testResults['mismatched'] }}
                        </div>
                        <div class="fi-wi-stats-overview-stat-description">
                            <x-filament::badge color="danger" size="sm">Different Category</x-filament::badge>
                        </div>
                    </div>
                </div>

                <div class="fi-wi-stats-overview-stat">
                    <div class="fi-wi-stats-overview-stat-content">
                        <div class="fi-wi-stats-overview-stat-label-ctn">
                            <span class="fi-wi-stats-overview-stat-label">JSON / API Errors</span>
                        </div>
                        <div class="fi-wi-stats-overview-stat-value">
                            {{ $testResults['invalid_json'] + $testResults['errors'] }}
                        </div>
                        <div class="fi-wi-stats-overview-stat-description">
                            <x-filament::badge color="warning" size="sm">Parse or network errors</x-filament::badge>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Benchmark Results Table inside Filament Section --}}
            <x-filament::section
                heading="Detailed Benchmark Results"
                description="Comparing AI predictions against human ground truth without altering database records."
            >
                <x-slot name="headerEnd">
                    <x-filament::button
                        type="button"
                        color="gray"
                        size="sm"
                        wire:click="clearTestResults"
                    >
                        Clear Results
                    </x-filament::button>
                </x-slot>

                <div class="fi-ta-ctn overflow-x-auto">
                    <table class="fi-ta-table">
                        <thead>
                            <tr>
                                <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 text-start text-sm font-semibold text-gray-950 dark:text-white">#</th>
                                <th class="fi-ta-header-cell px-3 py-3.5 text-start text-sm font-semibold text-gray-950 dark:text-white">Article Title</th>
                                <th class="fi-ta-header-cell px-3 py-3.5 text-start text-sm font-semibold text-gray-950 dark:text-white">Lang</th>
                                <th class="fi-ta-header-cell px-3 py-3.5 text-start text-sm font-semibold text-gray-950 dark:text-white">Expected (Ground Truth)</th>
                                <th class="fi-ta-header-cell px-3 py-3.5 text-start text-sm font-semibold text-gray-950 dark:text-white">AI Prediction</th>
                                <th class="fi-ta-header-cell px-3 py-3.5 text-center text-sm font-semibold text-gray-950 dark:text-white">Status</th>
                                <th class="fi-ta-header-cell px-3 py-3.5 sm:last-of-type:pe-6 text-end text-sm font-semibold text-gray-950 dark:text-white">AI Log</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                            @forelse ($testResults['results'] as $res)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition duration-75">
                                    <td class="fi-ta-cell px-3 py-4 sm:first-of-type:ps-6 text-xs text-gray-500 dark:text-gray-400">
                                        {{ $res['item_id'] }}
                                    </td>
                                    <td class="fi-ta-cell px-3 py-4 max-w-sm whitespace-normal">
                                        <a href="{{ $res['url'] }}" target="_blank" rel="noopener noreferrer" class="font-medium text-primary-600 hover:underline dark:text-primary-400 line-clamp-2">
                                            {{ $res['title'] }}
                                        </a>
                                    </td>
                                    <td class="fi-ta-cell px-3 py-4">
                                        <x-filament::badge color="gray" size="sm">
                                            {{ $res['language'] ?? '-' }}
                                        </x-filament::badge>
                                    </td>
                                    <td class="fi-ta-cell px-3 py-4 whitespace-normal">
                                        <x-filament::badge color="info" size="md">
                                            {{ $res['expected_category_name'] }}
                                        </x-filament::badge>
                                    </td>
                                    <td class="fi-ta-cell px-3 py-4 whitespace-normal">
                                        <x-filament::badge :color="$res['status'] === 'match' ? 'success' : 'danger'" size="md">
                                            {{ $res['predicted_category_name'] }}
                                        </x-filament::badge>
                                    </td>
                                    <td class="fi-ta-cell px-3 py-4 text-center">
                                        @if ($res['status'] === 'match')
                                            <x-filament::badge color="success" icon="heroicon-m-check">
                                                MATCH
                                            </x-filament::badge>
                                        @elseif ($res['status'] === 'mismatch')
                                            <x-filament::badge color="danger" icon="heroicon-m-x-mark">
                                                MISMATCH
                                            </x-filament::badge>
                                        @elseif ($res['status'] === 'invalid_json')
                                            <x-filament::badge color="warning" icon="heroicon-m-exclamation-triangle" :tooltip="$res['raw_response']">
                                                INVALID JSON
                                            </x-filament::badge>
                                        @else
                                            <x-filament::badge color="danger" icon="heroicon-m-exclamation-circle" :tooltip="$res['error_message']">
                                                ERROR
                                            </x-filament::badge>
                                        @endif
                                    </td>
                                    <td class="fi-ta-cell px-3 py-4 sm:last-of-type:pe-6 text-end">
                                        @if ($res['ai_request_log_id'])
                                            <x-filament::link :href="route('filament.admin.resources.ai.ai-request-logs.view', $res['ai_request_log_id'])" target="_blank" size="sm">
                                                #{{ $res['ai_request_log_id'] }}
                                            </x-filament::link>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No records evaluated.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-filament::section>
        </div>
    @endif
</x-filament-panels::page>
