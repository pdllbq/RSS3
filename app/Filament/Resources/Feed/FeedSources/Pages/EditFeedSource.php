<?php

namespace App\Filament\Resources\Feed\FeedSources\Pages;

use App\Filament\Resources\Feed\FeedSources\FeedSourceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditFeedSource extends EditRecord
{
    protected static string $resource = FeedSourceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
