<?php

namespace App\Domain\Feed\Data;

class FeedItemData
{
    public function __construct(
        public $title_raw,
        public $title_sanitized,
        public $image_url,
        public $description_raw,
        public $description_sanitized,
        public $content_raw,
        public $content_sanitized,
        public $link,
        public $permalink,
        public $date,
        public $author,
        public $authors,
        public $categories,
        public $remote_id,
        public $enclosure,
        public $enclosures,
    ) {}
}
