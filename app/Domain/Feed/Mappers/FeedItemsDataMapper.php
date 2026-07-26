<?php

namespace App\Domain\Feed\Mappers;

use App\Domain\Content\Services\ContentSanitizer;
use App\Domain\Feed\Actions\GetFeedItemImageUrlAction;
use App\Domain\Feed\Data\FeedItemData;

class FeedItemsDataMapper
{
    public function __construct(
        private ContentSanitizer $contentSanitizer,
        private GetFeedItemImageUrlAction $getFeedItemImageUrlAction
    ) {}

    public function fromSimplePieItem($items)
    {
        $mappedItems = [];

        foreach ($items as $item) {
            $author = $item->get_author();
            $authors = $item->get_authors();
            $categories = $item->get_categories();
            $enclosure = $item->get_enclosure();
            $enclosures = $item->get_enclosures();

            $mappedItems[] = new FeedItemData(
                title_raw: $item->get_title(),
                title_sanitized: $this->contentSanitizer->plainText($item->get_title()),
                image_url: $this->getFeedItemImageUrlAction->execute($item),
                description_raw: $item->get_description(),
                description_sanitized: $this->contentSanitizer->plainText($item->get_description()),
                content_raw: $item->get_content(),
                content_sanitized: $this->contentSanitizer->plainText($item->get_content()),
                link: $this->contentSanitizer->plainText($item->get_link()),
                permalink: $this->contentSanitizer->plainText($item->get_permalink()),
                date: $item->get_date('U'),
                author: $author ? $this->contentSanitizer->plainText($author->get_name()) : '',
                authors: is_array($authors) ? $authors : [],
                categories: is_array($categories) ? $categories : [],
                remote_id: $this->contentSanitizer->plainText($item->get_id()),
                enclosure: $enclosure,
                enclosures: is_array($enclosures) ? $enclosures : [],
            );
        }

        return $mappedItems;
    }
}
