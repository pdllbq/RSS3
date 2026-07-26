<?php

namespace App\Filament\Resources\Feed\GlobalCategories\Schemas;

use App\Models\Feed\GlobalCategory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GlobalCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category')
                    ->schema([
                        Select::make('parent_id')
                            ->label('Parent')
                            ->placeholder('Root category')
                            ->options(fn (?GlobalCategory $record): array => self::parentOptions($record))
                            ->searchable()
                            ->preload(),
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('slug')
                            ->required(),
                        TextInput::make('language'),
                    ])
                    ->columns(2),
            ]);
    }

    private static function parentOptions(?GlobalCategory $record): array
    {
        $excludedIds = $record
            ? array_merge([$record->getKey()], $record->descendantIds())
            : [];

        $categories = GlobalCategory::query()
            ->with('parent.ancestors')
            ->whereNotIn('id', $excludedIds)
            ->orderBy('name')
            ->get();

        $categoriesByParent = [];

        foreach ($categories as $category) {
            $categoriesByParent[self::parentGroupKey($category->parent_id)][] = $category;
        }

        $options = [];
        $appendedIds = [];

        self::appendParentOptions($options, $categoriesByParent, null, [], $appendedIds);

        foreach ($categories as $category) {
            if (! in_array($category->getKey(), $appendedIds, true)) {
                $options[$category->getKey()] = self::categoryPath($category);
                $appendedIds[] = $category->getKey();
            }
        }

        return $options;
    }

    private static function appendParentOptions(
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

            self::appendParentOptions(
                $options,
                $categoriesByParent,
                $category->getKey(),
                $categoryPath,
                $appendedIds,
            );
        }
    }

    private static function parentGroupKey(?int $parentId): string
    {
        return $parentId === null ? 'root' : (string) $parentId;
    }

    private static function categoryPath(GlobalCategory $category): string
    {
        $path = [];
        $visitedIds = [];
        $current = $category;

        while ($current !== null && ! in_array($current->getKey(), $visitedIds, true)) {
            array_unshift($path, $current->name);
            $visitedIds[] = $current->getKey();
            $current = $current->parent;
        }

        return implode(' / ', $path);
    }
}
