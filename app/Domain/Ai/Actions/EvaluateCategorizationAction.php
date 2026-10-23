<?php

namespace App\Domain\Ai\Actions;

use App\Models\Ai\AiModel;
use App\Models\Ai\AiPromptTemplate;
use App\Models\Feed\FeedItem;
use App\Models\Feed\GlobalCategory;
use App\Support\Json\JsonValidator;
use Throwable;

class EvaluateCategorizationAction
{
    public function __construct(
        protected GetChatAnswerAction $getChatAnswerAction,
        protected JsonValidator $jsonValidator,
    ) {}

    /**
     * @return array{
     *     total: int,
     *     matched: int,
     *     mismatched: int,
     *     invalid_json: int,
     *     errors: int,
     *     accuracy: float,
     *     model_name: string,
     *     model_type: string,
     *     prompt_name: string,
     *     results: array<int, array{
     *         item_id: int,
     *         title: string,
     *         url: ?string,
     *         language: ?string,
     *         expected_category_id: ?int,
     *         expected_category_name: string,
     *         predicted_category_id: ?int,
     *         predicted_category_name: string,
     *         status: string,
     *         error_message: ?string,
     *         ai_request_log_id: ?int,
     *         raw_response: string,
     *     }>
     * }
     */
    public function execute(
        AiModel $model,
        AiPromptTemplate $promptTemplate,
        int $limit = 10,
        ?string $language = null,
        bool $onlyManual = false,
    ): array {
        $query = FeedItem::query()
            ->with(['globalCategory', 'feedSource'])
            ->whereNotNull('global_category_id')
            ->when($language, fn ($q) => $q->where('language', $language))
            ->when($onlyManual, fn ($q) => $q->where('is_manually_categorized', true))
            ->orderBy('published_at', 'desc')
            ->orderBy('id', 'desc');

        if ($limit > 0) {
            $query->limit($limit);
        }

        $items = $query->get();

        $results = [];
        $matched = 0;
        $mismatched = 0;
        $invalidJsonCount = 0;
        $errors = 0;

        foreach ($items as $item) {
            $itemLanguage = $item->language ?: config('app.locale', 'ru');
            $categoriesJson = $this->getCategories($itemLanguage);

            $promptText = $this->fillPrompt($promptTemplate->user_prompt, [
                'categories_json' => $categoriesJson,
                'title' => $item->title,
                'content' => $item->content ?: $item->description ?: '',
            ]);

            $aiRequestLogId = null;
            $response = null;

            try {
                $response = $this->getChatAnswerAction->execute($promptText, $model, true, $aiRequestLogId);
            } catch (Throwable $e) {
                $errors++;
                $results[] = [
                    'item_id' => $item->id,
                    'title' => $item->title,
                    'url' => $item->url,
                    'language' => $item->language,
                    'expected_category_id' => $item->global_category_id,
                    'expected_category_name' => $item->globalCategory?->path ?? 'None',
                    'predicted_category_id' => null,
                    'predicted_category_name' => 'Error',
                    'status' => 'error',
                    'error_message' => $e->getMessage(),
                    'ai_request_log_id' => $aiRequestLogId,
                    'raw_response' => '',
                ];

                continue;
            }

            $isValidJson = $this->jsonValidator->isValid((string) $response);

            if (! $isValidJson) {
                $invalidJsonCount++;
                $results[] = [
                    'item_id' => $item->id,
                    'title' => $item->title,
                    'url' => $item->url,
                    'language' => $item->language,
                    'expected_category_id' => $item->global_category_id,
                    'expected_category_name' => $item->globalCategory?->path ?? 'None',
                    'predicted_category_id' => null,
                    'predicted_category_name' => 'Invalid JSON',
                    'status' => 'invalid_json',
                    'error_message' => null,
                    'ai_request_log_id' => $aiRequestLogId,
                    'raw_response' => (string) $response,
                ];

                continue;
            }

            $data = json_decode((string) $response, true);
            $predictedId = isset($data['category_id']) && is_numeric($data['category_id'])
                ? (int) $data['category_id']
                : null;

            $predictedCategory = $predictedId ? GlobalCategory::find($predictedId) : null;
            $predictedName = $predictedCategory ? $predictedCategory->path : ($predictedId ? "Unknown #{$predictedId}" : 'None');

            $isMatch = ($predictedId !== null && (int) $item->global_category_id === $predictedId);

            if ($isMatch) {
                $matched++;
                $status = 'match';
            } else {
                $mismatched++;
                $status = 'mismatch';
            }

            $results[] = [
                'item_id' => $item->id,
                'title' => $item->title,
                'url' => $item->url,
                'language' => $item->language,
                'expected_category_id' => $item->global_category_id,
                'expected_category_name' => $item->globalCategory?->path ?? 'None',
                'predicted_category_id' => $predictedId,
                'predicted_category_name' => $predictedName,
                'status' => $status,
                'error_message' => null,
                'ai_request_log_id' => $aiRequestLogId,
                'raw_response' => (string) $response,
            ];
        }

        $total = count($results);
        $accuracy = $total > 0 ? round(($matched / $total) * 100, 1) : 0.0;

        return [
            'total' => $total,
            'matched' => $matched,
            'mismatched' => $mismatched,
            'invalid_json' => $invalidJsonCount,
            'errors' => $errors,
            'accuracy' => $accuracy,
            'model_name' => $model->name,
            'model_type' => $model->type,
            'prompt_name' => $promptTemplate->name,
            'results' => $results,
        ];
    }

    public function getCategories(string $language): string
    {
        $categories = GlobalCategory::with('children:id,parent_id,name')
            ->where('language', $language)
            ->whereNull('parent_id')
            ->select('id', 'name')
            ->orderBy('id', 'ASC')
            ->get();

        if ($categories->isEmpty()) {
            return '[]';
        }

        $categories->each(function ($category) {
            $category->children->makeHidden('parent_id');
        });

        return $categories->toJson(JSON_UNESCAPED_UNICODE);
    }

    public function fillPrompt(string $prompt, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $prompt = str_replace('{{'.$key.'}}', (string) $value, $prompt);
        }

        return $prompt;
    }
}
