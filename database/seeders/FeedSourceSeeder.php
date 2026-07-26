<?php

namespace Database\Seeders;

use App\Models\Feed\FeedSource;
use Illuminate\Database\Seeder;

class FeedSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sources = [
            [
                'custom_title' => 'DELFI Latvia',
                'url' => 'https://www.delfi.lv/rss/index.xml',
                'language' => 'lv',
            ],
            [
                'custom_title' => 'TVNET Rus',
                'url' => 'https://rus.tvnet.lv/rss',
                'language' => 'ru',
            ],
            [
                'custom_title' => 'Gorod.lv',
                'url' => 'https://www.gorod.lv/rss',
                'language' => 'ru',
            ],
        ];

        foreach ($sources as $source) {
            FeedSource::updateOrCreate(
                ['url' => $source['url']],
                $source
            );
        }
    }
}
