<?php

namespace App\Filament\Resources\Feed\FeedItems\Pages;

use App\Filament\Resources\Feed\FeedItems\FeedItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFeedItem extends CreateRecord
{
    protected static string $resource = FeedItemResource::class;
}
