<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            [
                'name' => 'Class 1-5',
                'slug' => 'class-1-5',
                'icon' => '🎒',
                'sort_order' => 1,
                'seo_content' => 'Find qualified tutors for Class 1 to 5 students. Expert teachers for primary level education covering all subjects including Mathematics, English, Bangla, and Science.',
                'meta_title' => 'Class 1-5 Tutors in Bangladesh | Primary School Home Tutors',
                'meta_description' => 'Find experienced tutors for Class 1-5. Primary school teachers for all subjects. Verified tutors available.',
            ],
            [
                'name' => 'Class 6-8',
                'slug' => 'class-6-8',
                'icon' => '📚',
                'sort_order' => 2,
                'seo_content' => 'Looking for Class 6-8 tutors? Find qualified teachers for junior secondary level covering Mathematics, Science, English, Bangla, and other subjects.',
                'meta_title' => 'Class 6-8 Tutors in Bangladesh | Junior Secondary Home Tutors',
                'meta_description' => 'Experienced tutors for Class 6-8. All subjects covered by qualified teachers. JSC preparation available.',
            ],
            [
                'name' => 'Class 9-10 (SSC)',
                'slug' => 'class-9-10-ssc',
                'icon' => '📖',
                'sort_order' => 3,
                'seo_content' => 'Get expert SSC tutors for Class 9-10. Comprehensive preparation for all subjects including Mathematics, Physics, Chemistry, Biology, English, and Bangla.',
                'meta_title' => 'SSC Tutors in Bangladesh | Class 9-10 Home Tutors',
                'meta_description' => 'Find qualified SSC tutors for Class 9-10. All subjects covered. Expert guidance for board exams.',
            ],
            [
                'name' => 'Class 11-12 (HSC)',
                'slug' => 'class-11-12-hsc',
                'icon' => '🎓',
                'sort_order' => 4,
                'seo_content' => 'Find experienced HSC tutors for Class 11-12. Get expert guidance in Science, Commerce, and Arts subjects. University admission preparation available.',
                'meta_title' => 'HSC Tutors in Bangladesh | Class 11-12 Home Tutors',
                'meta_description' => 'Expert HSC tutors for Class 11-12. Science, Commerce, Arts subjects covered. Admission test prep.',
            ],
            [
                'name' => 'O Level',
                'slug' => 'o-level',
                'icon' => '🏫',
                'sort_order' => 5,
                'seo_content' => 'Looking for O Level tutors? Find qualified Cambridge O Level teachers for all subjects including Mathematics, Physics, Chemistry, Biology, Economics, and more.',
                'meta_title' => 'O Level Tutors in Bangladesh | Cambridge O Level Home Tutors',
                'meta_description' => 'Find experienced O Level tutors for Cambridge curriculum. All subjects covered by qualified teachers.',
            ],
            [
                'name' => 'A Level',
                'slug' => 'a-level',
                'icon' => '🎯',
                'sort_order' => 6,
                'seo_content' => 'Get expert A Level tutors for Cambridge Advanced Level. Specialized teachers for Mathematics, Physics, Chemistry, Biology, Economics, and Business Studies.',
                'meta_title' => 'A Level Tutors in Bangladesh | Cambridge A Level Home Tutors',
                'meta_description' => 'Expert A Level tutors for Cambridge curriculum. Specialized subject teachers available.',
            ],
            [
                'name' => 'Bachelor/Honors',
                'slug' => 'bachelor-honors',
                'icon' => '🎓',
                'sort_order' => 7,
                'seo_content' => 'Find university-level tutors for Bachelor and Honors programs. Expert guidance in Engineering, Business, Arts, Science, and other undergraduate courses.',
                'meta_title' => 'Bachelor Tutors in Bangladesh | University Level Home Tutors',
                'meta_description' => 'Qualified tutors for Bachelor/Honors programs. Engineering, Business, Science courses covered.',
            ],
            [
                'name' => 'Masters/Post-Graduate',
                'slug' => 'masters-post-graduate',
                'icon' => '👨‍🎓',
                'sort_order' => 8,
                'seo_content' => 'Looking for Masters level tutors? Get expert guidance for post-graduate studies, thesis supervision, and research work from qualified teachers.',
                'meta_title' => 'Masters Tutors in Bangladesh | Post-Graduate Home Tutors',
                'meta_description' => 'Expert tutors for Masters/Post-Graduate studies. Thesis guidance and research support available.',
            ],
            [
                'name' => 'Admission Test',
                'slug' => 'admission-test',
                'icon' => '✍️',
                'sort_order' => 9,
                'seo_content' => 'Prepare for university admission tests with expert tutors. Medical, Engineering, DU, IBA, and other admission test preparation with proven results.',
                'meta_title' => 'Admission Test Tutors in Bangladesh | Medical, Engineering, DU',
                'meta_description' => 'Expert admission test tutors for Medical, Engineering, DU, IBA. Proven track record of success.',
            ],
            [
                'name' => 'Professional Courses',
                'slug' => 'professional-courses',
                'icon' => '💼',
                'sort_order' => 10,
                'seo_content' => 'Find tutors for professional courses including IELTS, TOEFL, GRE, GMAT, SAT, and skill development courses. Expert guidance from certified instructors.',
                'meta_title' => 'Professional Course Tutors | IELTS, TOEFL, GRE, GMAT',
                'meta_description' => 'Qualified tutors for IELTS, TOEFL, GRE, GMAT, SAT. Certified instructors available.',
            ],
        ];

        foreach ($levels as $level) {
            Level::firstOrCreate(
                ['slug' => $level['slug']],
                $level
            );
        }

        $this->command->info('✓ ' . count($levels) . ' levels created successfully!');
    }
}
