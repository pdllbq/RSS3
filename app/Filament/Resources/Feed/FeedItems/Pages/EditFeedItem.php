<?php

namespace App\Filament\Resources\Feed\FeedItems\Pages;

use App\Domain\Feed\Actions\SetManualGlobalCategoryAction;
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

    protected function afterSave(): void
    {
        if (! $this->record->needs_category_check) {
            return;
        }

        if (! $this->record->global_category_id) {
            app(SetManualGlobalCategoryAction::class)->skipGlobalCategory($this->record);

            return;
        }

        app(SetManualGlobalCategoryAction::class)->execute(
            $this->record,
            (int) $this->record->global_category_id,
        );
    }
}
