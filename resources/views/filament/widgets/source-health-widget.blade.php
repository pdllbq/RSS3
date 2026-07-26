<x-filament-widgets::widget>
    <x-filament::section
        heading="Source health"
        description="Recent sync errors that need attention."
    >
        <div class="divide-y divide-gray-100 dark:divide-white/10">
            @forelse ($problemSources as $source)
                <a href="{{ $feedSourceResource::getUrl('view', ['record' => $source]) }}" class="block py-3 first:pt-0 last:pb-0">
                    <div class="flex items-center justify-between gap-3">
                        <span class="min-w-0 truncate text-sm font-medium text-gray-950 dark:text-white">{{ $source->custom_title }}</span>
                        <span class="shrink-0 text-xs text-gray-500 dark:text-gray-400">{{ $source->last_fetched_at?->diffForHumans() ?? 'Never fetched' }}</span>
                    </div>
                    <div class="mt-2 rounded-md border border-rose-200 bg-rose-50 px-3 py-2 text-xs text-rose-700 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-300">
                        {{ $source->last_error }}
                    </div>
                </a>
            @empty
                <div class="py-8 text-center text-sm text-gray-500 dark:text-gray-400">No recent source errors.</div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
