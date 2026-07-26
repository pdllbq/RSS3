<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\FeedSourceSeeder;
use Database\Seeders\GlobalCategorySeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\AiProviderSeeder;
use Database\Seeders\AiModelSeeder;
use Database\Seeders\AiPromptTemplateSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(FeedSourceSeeder::class);
        $this->call(GlobalCategorySeeder::class);
        $this->call(AiProviderSeeder::class);
        $this->call(AiModelSeeder::class);
        $this->call(AiPromptTemplateSeeder::class);
    }
}
