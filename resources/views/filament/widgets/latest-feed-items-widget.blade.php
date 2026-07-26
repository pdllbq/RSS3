<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Latest items</x-slot>
        <x-slot name="description">Newest content across all sources.</x-slot>
        <x-slot name="afterHeader">
            <a href="{{ $feedItemResource::getUrl('index') }}" class="text-sm font-medium text-amber-600 hover:text-amber-500 dark:text-amber-400">View all</a>
        </x-slot>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[680px] table-fixed divide-y divide-gray-200 text-left dark:divide-white/10">
                <thead>
                    <tr>
                        <th class="w-[44%] px-3 py-2 text-xs font-medium text-gray-500 dark:text-gray-400">Title</th>
                        <th class="w-[24%] px-3 py-2 text-xs font-medium text-gray-500 dark:text-gray-400">Source</th>
                        <th class="w-[18%] px-3 py-2 text-xs font-medium text-gray-500 dark:text-gray-400">Published</th>
                        <th class="w-[14%] px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                    @forelse ($recentItems as $item)
                        <tr class="transition hover:bg-gray-50 dark:hover:bg-white/5">
                            <td class="px-3 py-3">
                                <a href="{{ $feedItemResource::getUrl('view', ['record' => $item]) }}" class="block truncate text-sm font-medium text-gray-950 hover:text-amber-600 dark:text-white dark:hover:text-amber-400">
                                    {{ $item->title }}
                                </a>
                            </td>
                            <td class="px-3 py-3">
                                <span class="block truncate text-sm text-gray-600 dark:text-gray-300">{{ $item->feedSource?->custom_title ?? 'Unknown source' }}</span>
                            </td>
                            <td class="px-3 py-3">
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $item->published_at?->diffForHumans() ?? 'No date' }}</span>
                            </td>
                            <td class="px-3 py-3 text-right">
                                <span class="inline-flex rounded-md px-2 py-1 text-xs font-medium {{ $item->is_read ? 'bg-gray-100 text-gray-600 dark:bg-white/10 dark:text-gray-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-400/10 dark:text-amber-300' }}">
                                    {{ $item->is_read ? 'Read' : 'Unread' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-3 py-10 text-center text-sm text-gray-500 dark:text-gray-400">No feed items yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
