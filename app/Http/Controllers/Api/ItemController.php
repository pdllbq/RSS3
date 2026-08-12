<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feed\FeedItem;
use App\Models\Feed\GlobalCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    public function byCategory(Request $request, GlobalCategory $category): JsonResponse
    {
        $validated = $request->validate([
            'language' => ['required', 'string', Rule::in(config('app.supported_locales', ['ru', 'lv']))],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);

        $language = $validated['language'];
        $perPage = (int) ($validated['per_page'] ?? 36);

        if ($category->language !== $language) {
            abort(404);
        }

        $categoryIds = [
            $category->getKey(),
            ...$category->descendantIds(),
        ];

        $items = FeedItem::query()
            ->select([
                'id',
                'feed_source_id',
                'title',
                'url',
                'image_url',
                'author',
                'description',
                'language',
                'published_at',
                'global_category_id',
            ])
            ->where('language', $language)
            ->whereIn('global_category_id', $categoryIds)
            ->displayable()
            ->with([
                'feedSource:id,custom_title,source_title,url,site_url,source_favicon',
                'globalCategory:id,parent_id,name,slug,language',
            ])
            ->orderByDesc('published_at')
            ->paginate($perPage);

        return response()->json($items);
    }
}
