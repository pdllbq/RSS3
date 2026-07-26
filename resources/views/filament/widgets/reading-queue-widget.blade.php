<x-filament-widgets::widget>
    <x-filament::section
        heading="Reading queue"
        description="Sources with the largest unread backlog."
    >
        <div class="space-y-4">
            <div class="divide-y divide-gray-100 dark:divide-white/10">
                @forelse ($topSources as $source)
                    <a href="{{ $feedSourceResource::getUrl('view', ['record' => $source]) }}" class="block py-3 first:pt-0 last:pb-0">
                        <div class="flex items-center justify-between gap-3">
                            <span class="min-w-0 truncate text-sm font-medium text-gray-900 dark:text-white">{{ $source->custom_title }}</span>
                            <span class="shrink-0 rounded-md bg-amber-100 px-2 py-1 text-xs font-medium text-amber-700 dark:bg-amber-400/10 dark:text-amber-300">{{ number_format($source->unread_feed_items_count) }}</span>
                        </div>
                        <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ number_format($source->feed_items_count) }} total items
                        </div>
                    </a>
                @empty
                    <p class="py-6 text-center text-sm text-gray-500 dark:text-gray-400">No sources yet.</p>
                @endforelse
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
