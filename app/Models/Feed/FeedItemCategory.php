<?php

namespace App\Models\Feed;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FeedItemCategory extends Model
{
    public const EXCLUDED_MAPPING_TYPE = 'exclude';

    protected $fillable = [
        'term',
        'scheme',
        'label',
        'type',
        'language',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $category): void {
            if ($category->isExcludedFromCategoryMappings()) {
                $category->globalCategories()->detach();
            }
        });
    }

    public function scopeAvailableForCategoryMappings(Builder $query): Builder
    {
        return $query->where(function (Builder $query): void {
            $query
                ->whereNull('type')
                ->orWhereRaw('LOWER(TRIM(type)) != ?', [self::EXCLUDED_MAPPING_TYPE]);
        });
    }

    public function isExcludedFromCategoryMappings(): bool
    {
        return self::isExcludedMappingType($this->type);
    }

    public static function isExcludedMappingType(?string $type): bool
    {
        return strtolower(trim((string) $type)) === self::EXCLUDED_MAPPING_TYPE;
    }

    public function feedSources(): BelongsToMany
    {
        return $this->belongsToMany(FeedSource::class, 'feed_item_category_feed_source')
            ->withTimestamps();
    }

    public function feedItems(): BelongsToMany
    {
        return $this->belongsToMany(FeedItem::class, 'feed_item_category_feed_item')
            ->withTimestamps();
    }

    public function globalCategories(): BelongsToMany
    {
        return $this->belongsToMany(GlobalCategory::class, 'feed_item_category_global_category')
            ->withTimestamps();
    }
}
