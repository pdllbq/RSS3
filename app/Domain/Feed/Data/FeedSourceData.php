<?php

namespace App\Domain\Feed\Data;

class FeedSourceData
{
    public function __construct(
        public $title,
        public $description,
        public $link,
        public $permalink,
        public $language,
        public $author,
        public $authors,
        public $imageUrl,
        public $favicon,
        public $itemQuantity,
        public $rawData,
        public $items,
        public $error,
    ) {}
}
