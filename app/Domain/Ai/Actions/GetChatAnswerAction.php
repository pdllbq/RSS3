<?php
namespace App\Domain\Ai\Actions;

use App\Domain\Ai\Managers\AiClientManager;

class GetChatAnswerAction
{
    function __construct(
        protected AiClientManager $aiClientManager
    ) {}

    public function execute(string $text, $model, bool $checkJson = false, ?int &$aiRequestLogId = null)
    {
        return $this->aiClientManager->getClient($text, $model, $checkJson, $aiRequestLogId);
    }
}