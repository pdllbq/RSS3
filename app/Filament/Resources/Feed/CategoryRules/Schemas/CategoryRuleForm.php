<?php

namespace App\Filament\Resources\Feed\CategoryRules\Schemas;

use App\Models\Feed\CategoryRule;
use App\Models\Feed\FeedItemCategory;
use App\Models\Feed\GlobalCategory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoryRuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Rule')
                    ->schema([
                        Select::make('global_category_id')
                            ->label('Global category')
                            ->relationship('globalCategory', 'name')
                            ->getOptionLabelFromRecordUsing(fn (GlobalCategory $record): string => $record->path)
                            ->options(fn (): array => self::globalCategoryOptions())
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('feed_item_category_id')
                            ->label('Feed category')
                            ->relationship('feedItemCategory', 'term')
                            ->options(fn (): array => self::feedItemCategoryOptions())
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('type')
                            ->options([
                                CategoryRule::TYPE_INCLUDE => 'Include',
                                CategoryRule::TYPE_EXCLUDE => 'Exclude',
                            ])
                            ->default(CategoryRule::TYPE_INCLUDE)
                            ->required()
                            ->native(false),
                        TextInput::make('language')
                            ->maxLength(8),
                    ])
                    ->columns(2),
            ]);
    }

    private static function globalCategoryOptions(): array
    {
        $categories = GlobalCategory::query()
            ->with('parent.ancestors')
            ->orderBy('name')
            ->get();

        $categoriesByParent = [];

        foreach ($categories as $category) {
            $categoriesByParent[self::parentGroupKey($category->parent_id)][] = $category;
        }

        $options = [];
        $appendedIds = [];

        self::appendGlobalCategoryOptions($options, $categoriesByParent, null, [], $appendedIds);

        foreach ($categories as $category) {
            if (! in_array($category->getKey(), $appendedIds, true)) {
                $options[$category->getKey()] = $category->path;
                $appendedIds[] = $category->getKey();
            }
        }

        return $options;
    }

    private static function appendGlobalCategoryOptions(
        array &$options,
        array $categoriesByParent,
        ?int $parentId,
        array $path,
        array &$appendedIds,
    ): void {
        foreach ($categoriesByParent[self::parentGroupKey($parentId)] ?? [] as $category) {
            if (in_array($category->getKey(), $appendedIds, true)) {
                continue;
            }

            $categoryPath = [...$path, $category->name];

            $options[$category->getKey()] = implode(' / ', $categoryPath);
            $appendedIds[] = $category->getKey();

            self::appendGlobalCategoryOptions(
                $options,
                $categoriesByParent,
                $category->getKey(),
                $categoryPath,
                $appendedIds,
            );
        }
    }

    private static function feedItemCategoryOptions(): array
    {
        return FeedItemCategory::query()
            ->orderBy('term')
            ->get()
            ->mapWithKeys(fn (FeedItemCategory $category): array => [
                $category->getKey() => self::feedItemCategoryLabel($category),
            ])
            ->all();
    }

    private static function parentGroupKey(?int $parentId): string
    {
        return $parentId === null ? 'root' : (string) $parentId;
    }

    private static function feedItemCategoryLabel(FeedItemCategory $category): string
    {
        return $category->label
            ? "{$category->term} ({$category->label})"
            : $category->term;
    }
}
