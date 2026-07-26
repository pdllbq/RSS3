<?php

namespace App\Models\Feed;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FeedItem extends Model
{
    protected $fillable = [
        'feed_source_id',
        'guid',
        'title',
        'url',
        'image_url',
        'author',
        'description',
        'content',
        'language',
        'published_at',
        'fetched_at',
        'checksum',
        'raw_payload',
        'global_category_id',
        'ai_request_log_id',
        'is_read',
        'feed_item_cluster_id',
        'similarity_score',
        'is_similarity_checked',
        'is_cluster_main',
    ];

    protected $casts = [
        'global_category_id' => 'integer',
        'ai_request_log_id' => 'integer',
        'published_at' => 'datetime',
        'fetched_at' => 'datetime',
        'is_read' => 'boolean',
        'is_cluster_main' => 'boolean',
        'raw_payload' => 'array',
    ];

    public function scopeDisplayable($query)
    {
        return $query
            ->where('is_category_checked', true)
            ->where('needs_category_check', false)
            ->where('is_similarity_checked', true)
            ->where('is_cluster_main', true);
    }

    public function markAsClusterMain(): void
    {
        self::query()
            ->where('feed_item_cluster_id', $this->feed_item_cluster_id)
            ->where('id', '!=', $this->id)
            ->update(['is_cluster_main' => false]);

        $this->forceFill([
            'is_cluster_main' => true,
        ])->save();
    }

    public function embedding()
    {
        return $this->hasOne(ItemEmbedding::class);
    }

    public function feedSource(): BelongsTo
    {
        return $this->belongsTo(FeedSource::class);
    }

    public function globalCategory(): BelongsTo
    {
        return $this->belongsTo(GlobalCategory::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(FeedItemCategory::class, 'feed_item_category_feed_item')
            ->withTimestamps();
    }

    public function globalCategories(): BelongsToMany
    {
        return $this->belongsToMany(GlobalCategory::class, 'feed_item_global_category')
            ->withTimestamps();
    }

    public function cluster(): BelongsTo
    {
        return $this->belongsTo(FeedItemCluster::class, 'feed_item_cluster_id');
    }

    protected function formattedPublishedAt(): Attribute
    {
        return Attribute::make(
            get: function () {
                $date = $this->published_at->locale(app()->getLocale());

                return $date->diffInHours(now()) < 24
                    ? $date->diffForHumans()
                    : $date->translatedFormat('d F Y');
            },
        );
    }
}
