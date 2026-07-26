<?php

namespace Tests\Unit;

use App\Models\Ai\AiPromptTemplate;
use PHPUnit\Framework\TestCase;

class AiPromptTemplateTest extends TestCase
{
    public function test_model_defines_array_casts_for_prompt_template_payloads(): void
    {
        $template = new AiPromptTemplate();

        $this->assertSame('array', $template->getCasts()['model_variables']);
        $this->assertSame('array', $template->getCasts()['config']);
    }
}
