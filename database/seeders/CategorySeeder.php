<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'School', 'slug' => 'school', 'icon' => 'school', 'sort_order' => 1],
            ['name' => 'Board Exam', 'slug' => 'board-exam', 'icon' => 'assignment', 'sort_order' => 2],
            ['name' => 'English Medium', 'slug' => 'english-medium', 'icon' => 'language', 'sort_order' => 3],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('Categories created successfully!');
    }
}
