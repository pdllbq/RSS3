<?php

namespace App\Filament\Resources\Ai\AiRequestLogs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AiRequestLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('ai_model_id')
                    ->numeric(),
                TextEntry::make('ai_provider_id')
                    ->numeric(),
                TextEntry::make('status'),
                TextEntry::make('task')
                    ->placeholder('-'),
                TextEntry::make('prompt')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('response')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('tokens_input')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('tokens_output')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('tokens_total')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('cost')
                    ->money()
                    ->placeholder('-'),
                TextEntry::make('error_message')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('embedding_1024')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
