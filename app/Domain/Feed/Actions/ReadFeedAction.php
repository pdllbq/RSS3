<?php

namespace App\Domain\Feed\Actions;

use App\Domain\Feed\Mappers\FeedSourceDataMapper;
use SimplePie\SimplePie;

class ReadFeedAction
{
    public function __construct(
        private FeedSourceDataMapper $feedSourceDataMapper
    ) {}

    public function execute($feedSource)
    {
        $feed = new SimplePie;
        $feed->set_feed_url($feedSource->url);
        $feed->enable_cache(true);
        $feed->set_cache_duration(3600);
        $feed->set_stupidly_fast(true);
        $feed->set_useragent(
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) '.
            'AppleWebKit/537.36 (KHTML, like Gecko) '.
            'Chrome/122.0.0.0 Safari/537.36'
        );
        $feed->init();

        $feedData = $this->feedSourceDataMapper->fromSimplePie($feed);

        return $feedData;
    }
}
