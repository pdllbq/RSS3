<?php

namespace App\Domain\Feed\Actions;

use App\Models\Feed\FeedItem;
use App\Models\Feed\FeedItemClick;
use Illuminate\Http\Request;

class RecordFeedItemClickAction
{
    public function execute(FeedItem $feedItem, ?Request $request = null): FeedItemClick
    {
        return FeedItemClick::create([
            'feed_item_id' => $feedItem->id,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'referer' => $request?->header('referer'),
            'created_at' => now(),
        ]);
    }
}
