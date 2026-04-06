<?php

namespace App\Filament\Resources\Feed\FeedSources\Pages;

use App\Filament\Resources\Feed\FeedSources\FeedSourceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFeedSource extends ViewRecord
{
    protected static string $resource = FeedSourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
