<?php

namespace Tests\Feature;

use App\Domain\Ai\Actions\ItemSetGlobalCategoryAction;
use App\Models\Feed\GlobalCategory;
use App\Support\Json\JsonValidator;
use PHPUnit\Framework\TestCase;

class ItemSetGlobalCategoryActionTest extends TestCase
{
    public function test_it_persists_the_ai_request_log_id_when_category_is_set(): void
    {
        $item = new class {
            public ?int $ai_request_log_id = null;
            public ?int $global_category_id = null;
            public bool $is_category_checked = false;
            public bool $needs_category_check = false;
            public bool $saved = false;

            public function save(): void
            {
                $this->saved = true;
            }
        };

        $globalCategory = new class extends GlobalCategory {
            public static function find($id, $columns = ['*'])
            {
                return null;
            }
        };

        $action = new ItemSetGlobalCategoryAction(new JsonValidator(), $globalCategory);

        $action->execute($item, '{"category_id": 1}', 42);

        $this->assertSame(42, $item->ai_request_log_id);
        $this->assertTrue($item->saved);
    }
}
