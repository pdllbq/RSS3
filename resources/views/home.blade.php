<x-layouts.default.app>
    <x-slot name="title">{{ __('home.Title') }}</x-slot>

    <div class="container">
        <div class="row">
            <div class="col-12 my-3">
                <h3>{{ __('home.Latest Posts') }}</h3>
            </div>
        </div>
    </div>

    @if($feedItems->isEmpty())
        <div class="container">
            <div class="row">
                <div class="col-12 py-3">
                    <div class="p-3 border rounded-3 mb-0" style="border-color: #1a212e; background-color: #0a121d; color: #e5e7eb;">
                        {{ __('home.No posts yet') }}
                    </div>
                </div>
            </div>
        </div>
    @else
        <x-default.feedItems.feedList :feedItems="$feedItems" />
    @endif

    <div class="container my-4">
        <div class="d-flex justify-content-center">
            {{ $feedItems->links() }}
        </div>
    </div>

</x-layouts.default.app>