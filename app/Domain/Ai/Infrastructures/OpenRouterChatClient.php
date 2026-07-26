<?php

namespace App\Domain\Ai\Infrastructures;

use Laravel\Ai\Enums\Lab;
use function Laravel\Ai\agent;
use App\Models\Ai\AiRequestLog;
use App\Support\Json\JsonValidator;

class OpenRouterChatClient
{
    function __construct(
        protected JsonValidator $jsonValidator
    )
    {}

    public function generateChatResponse(string $text, $model, bool $checkJson = false, ?int &$aiRequestLogId = null): string
    {
        $timeout = (int) env('OPENROUTER_TIMEOUT', 300);

        $response = agent()
        ->prompt(
            $text,
            provider: Lab::OpenRouter,
            model: $model->provider_model_id,
            timeout: $timeout,
        );

        $isValidJson = null;
        if($checkJson){
            $isValidJson = $this->jsonValidator->isValid($response->text);
        }

        $log = AiRequestLog::create([
            'prompt' => $text,
            'response' => $response->text,
            'ai_model_id' => $model->id,
            'ai_provider_id' => $model->provider->id,
            'is_valid_json' => $isValidJson,
        ]);

        $aiRequestLogId = $log->id;

        return $response->text;
    }
}