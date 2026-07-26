<?php

namespace App\Models\Feed;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryRule extends Model
{
    public const TYPE_INCLUDE = 'include';

    public const TYPE_EXCLUDE = 'exclude';

    protected $fillable = [
        'global_category_id',
        'feed_item_category_id',
        'type',
        'language',
    ];

    protected $casts = [
        'global_category_id' => 'integer',
        'feed_item_category_id' => 'integer',
    ];

    public function globalCategory(): BelongsTo
    {
        return $this->belongsTo(GlobalCategory::class);
    }

    public function feedItemCategory(): BelongsTo
    {
        return $this->belongsTo(FeedItemCategory::class);
    }
}
