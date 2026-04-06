<?php

namespace App\Filament\Resources\Feed\FeedSources\Pages;

use App\Filament\Resources\Feed\FeedSources\FeedSourceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFeedSource extends CreateRecord
{
    protected static string $resource = FeedSourceResource::class;
}
