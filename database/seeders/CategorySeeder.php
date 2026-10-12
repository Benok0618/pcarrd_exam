<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Agriculture & Forestry', 'description' => 'Crops, livestock, and forestry research'],
            ['name' => 'Aquatic & Marine', 'description' => 'Fisheries and aquatic resources'],
            ['name' => 'Natural Resources & Environment', 'description' => 'Ecosystems, climate, and conservation'],
            ['name' => 'Food & Nutrition', 'description' => 'Food processing, safety, and nutrition studies'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']],
            );
        }
    }
}
