<x-filament-widgets::widget>
    <x-filament::section
        heading="Top categories"
        description="Most represented topics in the feed archive."
    >
        <div class="divide-y divide-gray-100 dark:divide-white/10">
            @forelse ($topCategories as $category)
                <div class="flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0">
                    <span class="min-w-0 truncate text-sm font-medium text-gray-900 dark:text-white">{{ $category->label ?: $category->term }}</span>
                    <span class="shrink-0 rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600 dark:bg-white/10 dark:text-gray-300">
                        {{ number_format($category->feed_items_count) }} items
                    </span>
                </div>
            @empty
                <div class="py-8 text-center text-sm text-gray-500 dark:text-gray-400">No categories yet.</div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
