<?php

namespace App\Filament\Resources\Feed\FeedItems\Pages;

use App\Filament\Resources\Feed\FeedItems\FeedItemResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFeedItem extends ViewRecord
{
    protected static string $resource = FeedItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
