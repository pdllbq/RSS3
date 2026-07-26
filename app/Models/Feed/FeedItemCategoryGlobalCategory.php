<?php

namespace App\Models\Feed;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

class FeedItemCategoryGlobalCategory extends Model
{
    protected $table = 'feed_item_category_global_category';

    protected $fillable = [
        'feed_item_category_id',
        'global_category_id',
    ];

    protected $casts = [
        'feed_item_category_id' => 'integer',
        'global_category_id' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $mapping): void {
            $category = $mapping->feedItemCategory()->first();

            if ($category?->isExcludedFromCategoryMappings()) {
                throw ValidationException::withMessages([
                    'feed_item_category_id' => 'Excluded feed categories cannot be mapped to global categories.',
                ]);
            }
        });
    }

    public function feedItemCategory(): BelongsTo
    {
        return $this->belongsTo(FeedItemCategory::class);
    }

    public function globalCategory(): BelongsTo
    {
        return $this->belongsTo(GlobalCategory::class);
    }
}
