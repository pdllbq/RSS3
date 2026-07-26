<?php

namespace Database\Seeders;

use App\Models\Feed\GlobalCategory;
use Illuminate\Database\Seeder;

class GlobalCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $novosti = GlobalCategory::updateOrCreate(
            ['slug' => 'novosti'],
            [
                'parent_id' => null,
                'name' => 'Новости',
                'language' => 'ru',
            ],
        );

        GlobalCategory::updateOrCreate(
            ['slug' => 'novosti/latviia'],
            [
                'parent_id' => $novosti->getKey(),
                'name' => 'Латвия',
                'language' => 'ru',
            ],
        );

        $zinas = GlobalCategory::updateOrCreate(
            ['slug' => 'zinas'],
            [
                'parent_id' => null,
                'name' => 'Ziņas',
                'language' => 'lv',
            ],
        );

        GlobalCategory::updateOrCreate(
            ['slug' => 'zinas/latvija'],
            [
                'parent_id' => $zinas->getKey(),
                'name' => 'Latvija',
                'language' => 'lv',
            ],
        );
    }
}
