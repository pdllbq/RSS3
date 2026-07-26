<x-filament-widgets::widget>
    <x-filament::section
        heading="Feeds overview"
        description="Current feed volume and source freshness."
    >
        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-gray-950">
                <div class="h-1 w-10 rounded-full bg-sky-500"></div>
                <div class="mt-3 text-xs font-medium text-gray-500 dark:text-gray-400">Active sources</div>
                <div class="mt-1 text-2xl font-semibold text-gray-950 dark:text-white">{{ number_format($activeSourcesCount) }}</div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-gray-950">
                <div class="h-1 w-10 rounded-full bg-indigo-500"></div>
                <div class="mt-3 text-xs font-medium text-gray-500 dark:text-gray-400">Feed items</div>
                <div class="mt-1 text-2xl font-semibold text-gray-950 dark:text-white">{{ number_format($itemsCount) }}</div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-gray-950">
                <div class="h-1 w-10 rounded-full bg-amber-500"></div>
                <div class="mt-3 text-xs font-medium text-gray-500 dark:text-gray-400">Unread</div>
                <div class="mt-1 text-2xl font-semibold text-amber-600 dark:text-amber-400">{{ number_format($unreadItemsCount) }}</div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-gray-950">
                <div class="h-1 w-10 rounded-full bg-emerald-500"></div>
                <div class="mt-3 text-xs font-medium text-gray-500 dark:text-gray-400">Added today</div>
                <div class="mt-1 text-2xl font-semibold text-emerald-600 dark:text-emerald-400">{{ number_format($itemsToday) }}</div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-gray-950">
                <div class="h-1 w-10 rounded-full {{ $staleSourcesCount > 0 ? 'bg-rose-500' : 'bg-gray-300 dark:bg-gray-700' }}"></div>
                <div class="mt-3 text-xs font-medium text-gray-500 dark:text-gray-400">Stale sources</div>
                <div class="mt-1 text-2xl font-semibold {{ $staleSourcesCount > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-gray-950 dark:text-white' }}">{{ number_format($staleSourcesCount) }}</div>
            </div>
        </div>

        <div class="mt-5 border-t border-gray-200 pt-4 dark:border-white/10">
            <div class="flex items-center justify-between text-sm">
                <span class="font-medium text-gray-700 dark:text-gray-200">Read progress</span>
                <span class="font-semibold text-gray-950 dark:text-white">{{ $readPercent }}%</span>
            </div>
            <div class="mt-2 h-2 overflow-hidden rounded-full bg-gray-100 dark:bg-white/10">
                <div class="h-full rounded-full bg-amber-500" style="width: {{ $readPercent }}%"></div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
