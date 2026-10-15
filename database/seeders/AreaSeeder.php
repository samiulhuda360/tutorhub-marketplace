<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            ['name' => 'Mirpur', 'slug' => 'mirpur', 'sort_order' => 1],
            ['name' => 'Dhanmondi', 'slug' => 'dhanmondi', 'sort_order' => 2],
            ['name' => 'Bashundhara', 'slug' => 'bashundhara', 'sort_order' => 3],
            ['name' => 'Gulshan', 'slug' => 'gulshan', 'sort_order' => 4],
            ['name' => 'Uttara', 'slug' => 'uttara', 'sort_order' => 5],
            ['name' => 'Mohammadpur', 'slug' => 'mohammadpur', 'sort_order' => 6],
        ];

        foreach ($areas as $area) {
            Area::create($area);
        }

        $this->command->info('Areas created successfully!');
    }
}
