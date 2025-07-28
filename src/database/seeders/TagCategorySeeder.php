<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\TagCategory;
use Illuminate\Database\Seeder;

class TagCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TagCategory::factory()->create();
    }
}
