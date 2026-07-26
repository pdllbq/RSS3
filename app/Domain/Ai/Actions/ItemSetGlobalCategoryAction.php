<?php
namespace App\Domain\Ai\Actions;

use App\Support\Json\JsonValidator;
use App\Models\Feed\GlobalCategory;

class ItemSetGlobalCategoryAction
{
    function __construct(
        protected JsonValidator $jsonValidator,
        protected GlobalCategory $globalCategory,
    ) {}

    public function execute($item, $category, ?int $aiRequestLogId = null)
    {
        if ($aiRequestLogId !== null) {
            $item->ai_request_log_id = $aiRequestLogId;
        }

        if ($this->jsonValidator->isValid($category)) {
            $this->json($item, $category);

            return 0;
        }

        $item->is_category_checked = true;
        $item->needs_category_check = true;
        $item->save();

        return 0;
    }

    protected function json($item, $category)
    {
        $categoryData = json_decode($category, true);

        if(isset($categoryData['category_id'])){
            $isCategoryParent = $this->isCategoryParent($categoryData['category_id']);

            $item->global_category_id = $categoryData['category_id'];
            $item->is_category_checked = true;
            $item->needs_category_check = $isCategoryParent;
            $item->save();

            return 0;
        }

        $item->is_category_checked = true;
        $item->needs_category_check = true;
        $item->save();

        return 0;
    }

    protected function isCategoryParent(int $categoryId): bool
    {
        $category = $this->globalCategory->find($categoryId);

        if(!$category) {
            return true; // Если категория не найдена, считаем, что нужна ручная проверка
        }

        $isParent = $category->children()->exists();

        return $isParent;
    }
}