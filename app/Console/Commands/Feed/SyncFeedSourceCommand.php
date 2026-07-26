<?php

namespace App\Console\Commands\Feed;

use App\Domain\Feed\Actions\SyncFeedSourceAction;
use App\Models\Feed\FeedSource;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('feed:sync')]
#[Description('Command description')]

class SyncFeedSourceCommand extends Command
{
    public function __construct(
        private SyncFeedSourceAction $syncFeedSourceAction
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting feed sync...');

        $source = FeedSource::query()
            ->orderByRaw('last_fetched_at ASC NULLS FIRST')
            ->first();

        if (! $source) {
            $this->warn('No feed sources found.');

            return self::SUCCESS;
        }

        $this->line('Syncing: '.($source->source_title ?: $source->url));

        $result = $this->syncFeedSourceAction->execute($source);

        if ($result->error) {
            $this->error('Error syncing feed: '.$result->error);
        } else {
            $this->info('Feed synced successfully.');
        }

        return self::SUCCESS;
    }
}
