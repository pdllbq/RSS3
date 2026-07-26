<?php

namespace Database\Seeders;

use App\Models\Ai\AiPromptTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AiPromptTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $template = [
            'name' => 'Content Category Classifier',
            'slug' => 'content-category-classifier',
            'type' => 'categorization',
            'task' => 'classification',
            'system_prompt' => 'You are a content classifier.',
            'user_prompt' => <<<'PROMPT'
You are a content classifier.

Your task is to choose the most suitable category ID for the article.

Categories are provided as a tree. A category may have subcategories.

Rules:
- Choose the most specific suitable category.
- If a suitable subcategory exists, return its id.
- If no suitable subcategory exists, but the parent category fits, return the parent category id.
- Use only ids from the provided category tree.
- Never invent ids.
- If nothing fits, return null.
- Return only valid JSON.
- Do not explain your choice.

Categories:

{{categories_json}}

Article:

Title:
{{title}}

Content:
{{content}}

Return exactly:

{
  "category_id": 123
}

If nothing matches:

{
  "category_id": null
}
PROMPT,
            'model_variables' => ['categories_json', 'title', 'content'],
            'config' => [
                'response_format' => 'json',
            ],
            'version' => '1.0',
            'is_active' => true,
        ];

        AiPromptTemplate::updateOrCreate(
            ['slug' => $template['slug']],
            $template
        );
    }
}
