<?php

namespace App\Console\Commands\Ai;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Feed\FeedItem;
use App\Models\Feed\FeedItemCluster;
use App\Models\Feed\ItemEmbedding;

#[Signature('items:match-cluster {--all : Process all unchecked items}')]
#[Description('Command description')]
class MatchClusterCommand extends Command
{
    /**
     * Команда ищет похожие элементы по векторным эмбеддингам и группирует их в кластеры.
     * - Если находит похожий элемент, использует его кластер (или создаёт новый),
     *   и присоединяет текущий элемент к этому кластеру с рассчитанным сходством.
     * - Если похожих не найдёт, создаёт новый кластер с текущим элементом как главным.
     */
    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Порог схожести: совпадения с similarity >= $threshold считаются похожими
        $threshold = 0.93;
        $processAll = $this->option('all');

        // Выбираем элементы, у которых есть эмбеддинг и которые ещё не проверены
        $query = FeedItem::has('embedding')->with('embedding')->where('is_similarity_checked', false)->orderBy('id', 'DESC');
        
        // Если указана опция --all, обрабатываем все непроверенные элементы,
        // иначе — только самый последний.
        if ($processAll) {
            $items = $query->get();
        } else {
            $items = collect([$query->first()])->filter();
        }

        if ($items->isEmpty()) {
            $this->info('No unchecked items.');
            return Command::SUCCESS;
        }

        $processedCount = 0;
        foreach ($items as $item) {
            // Получаем эмбеддинг для текущего элемента
            $embedding = $item->embedding->embedding_qwen3_8b_1536;

            // Приводим эмбеддинг к строке формата Postgres ::vector, если он в виде массива
            $vector = is_array($embedding)
                ? '[' . implode(',', $embedding) . ']'
                : (string) $embedding;

            // Выполняем поиск в таблице эмбеддингов: вычисляем similarity = 1 - distance
            // Оператор `<=>` возвращает расстояние между векторами (pgvector)
            // Фильтруем по порогу и исключаем сам элемент по id
            $results = ItemEmbedding::query()
                ->selectRaw(
                    '*, 1 - (embedding_qwen3_8b_1536 <=> ?::vector) as similarity',
                    [$vector]
                )
                ->where('feed_item_id', '!=', $item->id)
                ->whereRaw(
                    '1 - (embedding_qwen3_8b_1536 <=> ?::vector) >= ?',
                    [$vector, $threshold]
                )
                ->orderByRaw(
                    'embedding_qwen3_8b_1536 <=> ?::vector',
                    [$vector]
                )
                ->get();

            $this->line('Found '.count($results).' similar items:');
            $result = $results->first();

            if ($result) {
                // Нашли похожий элемент — берём связанный FeedItem
                $firstItem = FeedItem::find($result->feed_item_id);

                if ($firstItem->feed_item_cluster_id) {
                    // Если у найденного элемента уже есть кластер — используем его
                    $clusterId = $firstItem->feed_item_cluster_id;
                } else {
                    // Иначе — создаём новый кластер с найденным элементом как главным
                    $cluster = FeedItemCluster::create([
                        'main_feed_item_id' => $firstItem->id,
                    ]);

                    $clusterId = $cluster->id;

                    // Помечаем найденный элемент как главный в кластере (similarity=1)
                    $firstItem->update([
                        'feed_item_cluster_id' => $clusterId,
                        'similarity_score' => 1,
                    ]);
                    $firstItem->markAsClusterMain();
                }

                // similarity берём из результата SQL-запроса
                $similarity = $result->similarity;
            } else {
                // Похожих элементов не найдено — создаём новый кластер на основе текущего элемента
                $cluster = FeedItemCluster::create([
                    'main_feed_item_id' => $item->id,
                ]);

                $clusterId = $cluster->id;
                $similarity = 1;
            }

            // Обновляем текущий элемент: пометка проверенности и присвоение кластера
            $item->update([
                'is_similarity_checked' => true,
                'feed_item_cluster_id' => $clusterId,
                'similarity_score' => $similarity,
            ]);

            if ($similarity === 1) {
                $item->markAsClusterMain();
            }

            $processedCount++;
        }

        if ($processAll) {
            $this->info("Processed $processedCount items.");
        }

        return Command::SUCCESS;
    }
}
