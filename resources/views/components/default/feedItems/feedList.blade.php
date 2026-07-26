<div class="feed-list">
    @foreach ($feedItems as $feedItem)
        <x-default.feedItems.feedItem :feedItem="$feedItem" />
    @endforeach
</div>