<div class="container">
    <div class="row">
        <div class="col-12">
            <article data-url="{{ $feedItem->url }}" class="d-flex gap-3 py-3 px-3 my-2 feed-item">
                <div class=" col-auto feed-item-image-container">
                    <img src="{{ $feedItem->image_url }}" alt="{{ $feedItem->title }}" class="feed-item-image object-fit-cover rounded" />
                </div>
                <div class="col feed-item-content">
                    <p class="p-0 m-0">
                        <a href="{{ $feedItem->feedSource->source_link }}" target="_blank" rel="noopener noreferrer" class="feed-item-feed-name text-decoration-none">
                            {{ $feedItem->feedSource->name() }}
                        </a>
                    </p>
                    <h4 class="feed-item-title"><a target="_blank" href="{{ $feedItem->url }}">{{ $feedItem->title }}</a></h4>
                    <p class="feed-item-description">{{ $feedItem->description }}</p>
                </div>
                <div class="col-auto feed-item-date d-flex flex-column align-items-end">
                    <span>{{ $feedItem->formattedPublishedAt }}</span>

                    <a href="{{ route('system.info', ['itemId' => $feedItem->id]) }}" class="small text-decoration-none mt-auto" title="System info">
                            s
                    </a>
                </div>
            </article>
        </div>
    </div>
</div>