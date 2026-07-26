<?php

namespace App\Models\Feed;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeedSource extends Model
{
    protected $fillable = [
        'source_title',
        'custom_title',
        'url',
        'site_url',
        'source_description',
        'language',
        'source_link',
        'source_permalink',
        'source_date',
        'source_author',
        'source_authors',
        'source_image_url',
        'source_favicon',
        'source_item_quantity',
        'source_raw_data',
        'added_by',
        'user_id',
        'is_active',
        'last_fetched_at',
        'last_success_at',
        'last_error',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'source_authors' => 'array',
        'source_raw_data' => 'array',
        'source_date' => 'datetime',
        'last_fetched_at' => 'datetime',
        'last_success_at' => 'datetime',
    ];

    public function name(): string
    {
        return $this->custom_title ?? $this->source_title;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(FeedItemCategory::class, 'feed_item_category_feed_source')
            ->withTimestamps();
    }

    public function feedItems(): HasMany
    {
        return $this->hasMany(FeedItem::class);
    }
}
