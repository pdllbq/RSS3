<?php

namespace App\Domain\Feed\Actions;

class SyncFeedSourceAction
{
    public function __construct(
        private ReadFeedAction $readFeedAction,
        private SyncFeedItemsAction $syncFeedItemsAction
    ) {}

    public function execute($feedSource)
    {
        $feed = $this->readFeedAction->execute($feedSource);

        $this->updateFeedSource($feedSource, $feed);

        if (! $feed->error) {
            $items = $this->syncFeedItemsAction->execute($feedSource, $feed->items);
            $feed->items = $items;
        }

        return $feed;
    }

    protected function updateFeedSource($feedSource, $feedData)
    {

        $feedSource->fill([
            'source_title' => $feedData->title,
            'source_description' => $feedData->description,
            'source_link' => $feedData->link,
            'source_permalink' => $feedData->permalink,
            'language' => $feedData->language ?: $feedSource->language,
            'source_author' => $feedData->author,
            'source_authors' => $feedData->authors,
            'source_image_url' => $feedData->imageUrl,
            'source_favicon' => $feedData->favicon,
            'source_item_quantity' => $feedData->itemQuantity,
            'last_fetched_at' => now(),
            'last_error' => $feedData->error,
        ])->save();
    }
}
