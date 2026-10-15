<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Area;
use Illuminate\Support\Str;

class DhakaSubLocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, ensure Dhaka main area exists
        $dhaka = Area::firstOrCreate(
            ['slug' => 'dhaka'],
            [
                'name' => 'Dhaka',
                'is_active' => true,
                'sort_order' => 1,
                'parent_id' => null,
                'seo_content' => 'Dhaka, the capital city of Bangladesh, is home to thousands of qualified tutors. Whether you need home tuition, online tutoring, or group classes, TutorHub connects you with experienced tutors across all areas of Dhaka.',
                'meta_title' => 'Tutors in Dhaka | Find Best Home Tutors in Dhaka',
                'meta_description' => 'Find qualified home tutors in Dhaka. 1000+ verified tutors for all subjects and levels. Free tutor matching service.',
            ]
        );

        // Popular Dhaka sub-locations
        $subLocations = [
            ['name' => 'Dhanmondi', 'slug' => 'dhanmondi', 'sort_order' => 1],
            ['name' => 'Gulshan', 'slug' => 'gulshan', 'sort_order' => 2],
            ['name' => 'Banani', 'slug' => 'banani', 'sort_order' => 3],
            ['name' => 'Mirpur', 'slug' => 'mirpur', 'sort_order' => 4],
            ['name' => 'Uttara', 'slug' => 'uttara', 'sort_order' => 5],
            ['name' => 'Mohammadpur', 'slug' => 'mohammadpur', 'sort_order' => 6],
            ['name' => 'Bashundhara', 'slug' => 'bashundhara', 'sort_order' => 7],
            ['name' => 'Motijheel', 'slug' => 'motijheel', 'sort_order' => 8],
            ['name' => 'Paltan', 'slug' => 'paltan', 'sort_order' => 9],
            ['name' => 'Kakrail', 'slug' => 'kakrail', 'sort_order' => 10],
            ['name' => 'Shantinagar', 'slug' => 'shantinagar', 'sort_order' => 11],
            ['name' => 'Malibagh', 'slug' => 'malibagh', 'sort_order' => 12],
            ['name' => 'Rampura', 'slug' => 'rampura', 'sort_order' => 13],
            ['name' => 'Badda', 'slug' => 'badda', 'sort_order' => 14],
            ['name' => 'Mohakhali', 'slug' => 'mohakhali', 'sort_order' => 15],
            ['name' => 'Farmgate', 'slug' => 'farmgate', 'sort_order' => 16],
            ['name' => 'Tejgaon', 'slug' => 'tejgaon', 'sort_order' => 17],
            ['name' => 'Kalabagan', 'slug' => 'kalabagan', 'sort_order' => 18],
            ['name' => 'Green Road', 'slug' => 'green-road', 'sort_order' => 19],
            ['name' => 'Lalmatia', 'slug' => 'lalmatia', 'sort_order' => 20],
        ];

        foreach ($subLocations as $location) {
            // Check if area exists
            $existingArea = Area::where('slug', $location['slug'])->first();

            if ($existingArea) {
                // Update existing area to be a sub-location of Dhaka
                $existingArea->update([
                    'parent_id' => $dhaka->id,
                    'sort_order' => $location['sort_order'],
                    'seo_content' => "Looking for tutors in {$location['name']}, Dhaka? Find experienced home tutors for all subjects including Mathematics, English, Physics, Chemistry, and more. Our verified tutors in {$location['name']} provide quality education at affordable rates.",
                    'meta_title' => "Tutors in {$location['name']}, Dhaka | Home Tutors in {$location['name']}",
                    'meta_description' => "Find qualified home tutors in {$location['name']}, Dhaka. Verified tutors for all subjects and levels. Contact directly with no extra fees.",
                ]);
            } else {
                // Create new area
                Area::create([
                    'name' => $location['name'],
                    'slug' => $location['slug'],
                    'parent_id' => $dhaka->id,
                    'is_active' => true,
                    'sort_order' => $location['sort_order'],
                    'seo_content' => "Looking for tutors in {$location['name']}, Dhaka? Find experienced home tutors for all subjects including Mathematics, English, Physics, Chemistry, and more. Our verified tutors in {$location['name']} provide quality education at affordable rates.",
                    'meta_title' => "Tutors in {$location['name']}, Dhaka | Home Tutors in {$location['name']}",
                    'meta_description' => "Find qualified home tutors in {$location['name']}, Dhaka. Verified tutors for all subjects and levels. Contact directly with no extra fees.",
                ]);
            }
        }

        $this->command->info('✓ Dhaka and ' . count($subLocations) . ' sub-locations created successfully!');
    }
}
