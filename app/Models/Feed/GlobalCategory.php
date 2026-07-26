<?php

namespace App\Models\Feed;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

class GlobalCategory extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'language',
    ];

    protected $casts = [
        'parent_id' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $category): void {
            if ($category->parent_id === null || ! $category->exists) {
                return;
            }

            $parentId = $category->parent_id;
            $visitedIds = [];

            while ($parentId !== null) {
                if ($parentId === $category->getKey()) {
                    throw ValidationException::withMessages([
                        'parent_id' => 'A category cannot be nested inside itself or one of its descendants.',
                    ]);
                }

                if (in_array($parentId, $visitedIds, true)) {
                    return;
                }

                $visitedIds[] = $parentId;
                $parentId = self::query()->whereKey($parentId)->value('parent_id');
            }
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('name');
    }

    public function ancestors(): BelongsTo
    {
        return $this->parent()->with('ancestors');
    }

    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    public function getPathAttribute(): string
    {
        $path = [];
        $visitedIds = [];
        $current = $this;

        while ($current !== null && ! in_array($current->getKey(), $visitedIds, true)) {
            array_unshift($path, $current->name);
            $visitedIds[] = $current->getKey();
            $current = $current->parent;
        }

        return implode(' / ', $path);
    }

    public function descendantIds(): array
    {
        $ids = [];

        foreach (self::query()->where('parent_id', $this->getKey())->get(['id']) as $child) {
            $ids[] = $child->getKey();
            $ids = array_merge($ids, $child->descendantIds());
        }

        return $ids;
    }

    public function feedItems(): BelongsToMany
    {
        return $this->belongsToMany(FeedItem::class, 'feed_item_global_category')
            ->withTimestamps();
    }

    public function feedItemCategories(): BelongsToMany
    {
        return $this->belongsToMany(FeedItemCategory::class, 'feed_item_category_global_category')
            ->withTimestamps();
    }
}
