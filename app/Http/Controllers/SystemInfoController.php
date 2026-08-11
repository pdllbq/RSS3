<?php

namespace App\Http\Controllers;

use App\Models\Feed\FeedItem;
use Illuminate\Support\Facades\DB;
use Throwable;

class SystemInfoController extends Controller
{
    public function index(int $itemId)
    {
        $feedItem = FeedItem::query()
            ->with([
                'feedSource',
                'globalCategory',
                'globalCategories',
                'categories',
                'cluster',
                'embedding',
                'aiRequestLog.model',
                'aiRequestLog.provider',
            ])
            ->findOrFail($itemId);

        [$embeddingMatches, $embeddingError] = $this->getEmbeddingMatches($feedItem);

        $clusterItems = collect();

        if ($feedItem->feed_item_cluster_id) {
            $clusterItems = FeedItem::query()
                ->with(['feedSource', 'globalCategory'])
                ->where('feed_item_cluster_id', $feedItem->feed_item_cluster_id)
                ->whereKeyNot($feedItem->id)
                ->orderByDesc('similarity_score')
                ->limit(10)
                ->get();
        }

        return view('systemInfo', [
            'feedItem' => $feedItem,
            'aiRequestLog' => $feedItem->aiRequestLog,
            'embeddingMatches' => $embeddingMatches,
            'embeddingError' => $embeddingError,
            'clusterItems' => $clusterItems,
        ]);
    }

    protected function getEmbeddingMatches(FeedItem $feedItem): array
    {
        $embedding = $feedItem->embedding?->embedding_qwen3_8b_1536;

        if (! $embedding) {
            return [collect(), null];
        }

        $vector = $this->vectorToSqlLiteral($embedding);

        if (! $vector) {
            return [collect(), 'Embedding is empty or has an unsupported format.'];
        }

        try {
            $matches = DB::table('item_embeddings')
                ->join('feed_items', 'feed_items.id', '=', 'item_embeddings.feed_item_id')
                ->leftJoin('feed_sources', 'feed_sources.id', '=', 'feed_items.feed_source_id')
                ->leftJoin('global_categories', 'global_categories.id', '=', 'feed_items.global_category_id')
                ->select([
                    'feed_items.id',
                    'feed_items.title',
                    'feed_items.url',
                    'feed_items.published_at',
                    'feed_items.similarity_score as cluster_similarity_score',
                    'feed_items.feed_item_cluster_id',
                    'feed_items.is_cluster_main',
                    'feed_sources.source_title',
                    'feed_sources.custom_title',
                    'global_categories.name as global_category_name',
                ])
                ->selectRaw('1 - (item_embeddings.embedding_qwen3_8b_1536 <=> ?::vector) as embedding_similarity', [$vector])
                ->where('item_embeddings.feed_item_id', '!=', $feedItem->id)
                ->whereNotNull('item_embeddings.embedding_qwen3_8b_1536')
                ->orderByRaw('item_embeddings.embedding_qwen3_8b_1536 <=> ?::vector', [$vector])
                ->limit(10)
                ->get();

            return [$matches, null];
        } catch (Throwable $exception) {
            return [collect(), $exception->getMessage()];
        }
    }

    protected function vectorToSqlLiteral(array|string $embedding): ?string
    {
        if (is_string($embedding)) {
            return trim($embedding) !== '' ? $embedding : null;
        }

        if ($embedding === []) {
            return null;
        }

        return '['.implode(',', $embedding).']';
    }
}
