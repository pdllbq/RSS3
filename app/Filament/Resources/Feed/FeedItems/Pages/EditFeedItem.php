<?php

namespace App\Filament\Resources\Feed\FeedItems\Pages;

use App\Filament\Resources\Feed\FeedItems\FeedItemResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditFeedItem extends EditRecord
{
    protected static string $resource = FeedItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
