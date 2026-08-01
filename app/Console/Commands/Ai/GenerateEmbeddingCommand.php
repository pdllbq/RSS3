<?php

namespace App\Console\Commands\Ai;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

use App\Domain\Ai\Actions\GenerateEmbeddingAction;
use App\Models\Ai\AiModel;
use App\Models\Feed\FeedItem;

#[Signature('items:generate-embedding {--all}')] 
#[Description('Generate embeddings for feed items (use --all to process all missing)')]
class GenerateEmbeddingCommand extends Command
{
    function __construct(
        private GenerateEmbeddingAction $generateEmbeddingAction
    )
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('all')) {
            return $this->allItems();
        }

        return $this->oneItem();
    }

    protected function oneItem()
    {
        $item = FeedItem::doesntHave('embedding')->orderBy('id', 'DESC')->first();

        if (! $item) {
            $this->info('No items found that need embeddings.');

            return self::SUCCESS;
        }

        $model = AiModel::query()->where('type', 'embedding')->with('provider')->orderBy('id', 'DESC')->first();

        $this->line('Using model: '.$model->name);

        $this->processItem($item, $model);

        return self::SUCCESS;
    }

    protected function allItems()
    {
        $query = FeedItem::doesntHave('embedding')->orderBy('id', 'DESC');

        $count = $query->count();

        if ($count === 0) {
            $this->info('No items found that need embeddings.');

            return self::SUCCESS;
        }

        $this->line('Generating embeddings for '.$count.' items...');

        $model = AiModel::query()->where('type', 'embedding')->with('provider')->orderBy('id', 'DESC')->first();

        foreach ($query->cursor() as $item) {
            $this->processItem($item, $model);
        }

        return self::SUCCESS;
    }

    protected function processItem(FeedItem $item, AiModel $model): void
    {
        $this->line('Generating embedding for item: '.$item->title);

        $embedding = $this->generateEmbeddingAction->execute($item->title.PHP_EOL.$item->description, $model);

        $item->embedding()->create([
            'model' => $model->name,
            'embedding_qwen3_8b_1536' => $embedding,
        ]);

        $this->line('Embedding generated: '.json_encode(count($embedding)));
    }

}
