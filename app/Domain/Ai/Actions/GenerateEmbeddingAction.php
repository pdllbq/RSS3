<?php
namespace App\Domain\Ai\Actions;

use App\Domain\Ai\Managers\AiClientManager;
use App\Models\Ai\AiModel;

class GenerateEmbeddingAction
{

    function __construct(
        protected AiClientManager $aiClientManager
    ) {}

    public function execute(string $text, AiModel $model)
    {
        return $this->aiClientManager->getClient($text, $model);
    }
}