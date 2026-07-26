<?php

namespace App\Domain\Feed\Mappers;

use App\Domain\Content\Services\ContentSanitizer;
use App\Domain\Feed\Data\FeedSourceData;

class FeedSourceDataMapper
{
    public function __construct(
        private ContentSanitizer $contentSanitizer
    ) {}

    public function fromSimplePie($feed)
    {
        $authors = $feed->get_authors();
        $items = $feed->get_items();

        return new FeedSourceData(
            title: $this->contentSanitizer->plainText($feed->get_title()),
            description: $this->contentSanitizer->plainText($feed->get_description()),
            link: $this->contentSanitizer->plainText($feed->get_link()),
            permalink: $this->contentSanitizer->plainText($feed->get_permalink()),
            language: $this->contentSanitizer->plainText($feed->get_language()),
            author: $this->contentSanitizer->plainText($feed->get_author()),
            authors: is_array($authors) ? $authors : [],
            imageUrl: $this->contentSanitizer->plainText($feed->get_image_url()),
            favicon: $this->contentSanitizer->plainText($feed->get_favicon()),
            itemQuantity: $feed->get_item_quantity() ?? 0,
            rawData: $feed->get_raw_data() ?? '',
            items: is_array($items) ? $items : [],
            error: $feed->error(),
        );
    }

    private function sanitize($feed, $value, int $type): string
    {
        if (! is_string($value)) {
            return '';
        }

        return $feed->sanitize($value, $type);
    }
}
