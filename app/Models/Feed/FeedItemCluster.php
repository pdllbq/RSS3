<?php

namespace App\Models\Feed;

use Illuminate\Database\Eloquent\Model;

class FeedItemCluster extends Model
{
    protected $fillable = [
        'main_feed_item_id',
    ];

    public function mainFeedItem()
    {
        return $this->belongsTo(FeedItem::class, 'main_feed_item_id');
    }

    public function feedItems()
    {
        return $this->hasMany(FeedItem::class, 'feed_item_cluster_id');
    } 
}
