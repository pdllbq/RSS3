<?php

namespace App\Models\Feed;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedItemClick extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'feed_item_id',
        'ip_address',
        'user_agent',
        'referer',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function feedItem(): BelongsTo
    {
        return $this->belongsTo(FeedItem::class);
    }
}
