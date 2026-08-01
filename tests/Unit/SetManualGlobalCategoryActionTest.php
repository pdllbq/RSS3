<?php

namespace Tests\Unit;

use App\Domain\Feed\Actions\SetManualGlobalCategoryAction;
use App\Models\Feed\FeedItem;
use App\Models\Feed\GlobalCategory;
use InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\TestCase;

class SetManualGlobalCategoryActionTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_it_sets_a_leaf_category_and_completes_the_check(): void
    {
        $query = Mockery::mock();
        $category = Mockery::mock(GlobalCategory::class);
        $category->shouldReceive('getKey')->once()->andReturn(7);

        $query->shouldReceive('where')->with('id', 7)->andReturnSelf();
        $query->shouldReceive('where')->with('language', 'en')->andReturnSelf();
        $query->shouldReceive('whereDoesntHave')->with('children')->andReturnSelf();
        $query->shouldReceive('first')->once()->andReturn($category);

        $globalCategory = Mockery::mock(GlobalCategory::class);
        $globalCategory->shouldReceive('newQuery')->once()->andReturn($query);

        /** @var FeedItem $item */
        $item = Mockery::mock(FeedItem::class)->makePartial();
        $item->language = 'en';
        $item->shouldReceive('forceFill')->with([
            'global_category_id' => 7,
            'is_category_checked' => true,
            'needs_category_check' => false,
        ])->once()->andReturnSelf();
        $item->shouldReceive('save')->once();

        (new SetManualGlobalCategoryAction($globalCategory))->execute($item, 7);

        $this->addToAssertionCount(1);
    }

    public function test_it_rejects_a_category_that_is_not_a_leaf_for_the_item_language(): void
    {
        $query = Mockery::mock();
        $query->shouldReceive('where')->with('id', 7)->andReturnSelf();
        $query->shouldReceive('where')->with('language', 'en')->andReturnSelf();
        $query->shouldReceive('whereDoesntHave')->with('children')->andReturnSelf();
        $query->shouldReceive('first')->once()->andReturnNull();

        $globalCategory = Mockery::mock(GlobalCategory::class);
        $globalCategory->shouldReceive('newQuery')->once()->andReturn($query);

        /** @var FeedItem $item */
        $item = Mockery::mock(FeedItem::class)->makePartial();
        $item->language = 'en';

        $this->expectException(InvalidArgumentException::class);

        (new SetManualGlobalCategoryAction($globalCategory))->execute($item, 7);
    }

    public function test_it_can_mark_an_item_as_not_belonging_to_a_global_category(): void
    {
        /** @var FeedItem $item */
        $item = Mockery::mock(FeedItem::class)->makePartial();
        $item->shouldReceive('forceFill')->with([
            'global_category_id' => null,
            'is_category_checked' => true,
            'needs_category_check' => false,
        ])->once()->andReturnSelf();
        $item->shouldReceive('save')->once();

        $action = new SetManualGlobalCategoryAction(Mockery::mock(GlobalCategory::class));
        $action->skipGlobalCategory($item);

        $this->addToAssertionCount(1);
    }
}
