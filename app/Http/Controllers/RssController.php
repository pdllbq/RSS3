<?php

namespace App\Http\Controllers;

use App\Domain\Feed\Actions\GetRssFeedItemsAction;
use App\Models\Feed\GlobalCategory;
use Illuminate\Http\Response;

class RssController extends Controller
{
    public function index(string $lang, GetRssFeedItemsAction $getRssFeedItems): Response
    {
        $items = $getRssFeedItems->execute($lang);

        return $this->rssResponse($lang, $items);
    }

    public function category(string $lang, string $categorySlug, GetRssFeedItemsAction $getRssFeedItems): Response
    {
        $category = GlobalCategory::query()
            ->where('language', $lang)
            ->where('slug', $categorySlug)
            ->firstOrFail();

        $items = $getRssFeedItems->execute($lang, $category);

        return $this->rssResponse($lang, $items, $category);
    }

    private function rssResponse(string $lang, $items, ?GlobalCategory $category = null): Response
    {
        $title = $category
            ? config('app.name').' - '.$category->path
            : config('app.name').' - '.strtoupper($lang);

        $description = $category
            ? 'Latest posts for '.$category->path
            : 'Latest posts for '.strtoupper($lang);

        return response()
            ->view('rss.feed', [
                'category' => $category,
                'description' => $description,
                'items' => $items,
                'language' => $lang,
                'lastBuildDate' => $items->first()?->published_at ?? now(),
                'link' => route('home', ['lang' => $lang]),
                'selfLink' => $category
                    ? route('rss.category', ['lang' => $lang, 'categorySlug' => $category->slug])
                    : route('rss.index', ['lang' => $lang]),
                'title' => $title,
            ])
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }
}
