<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            [
                'name' => 'Mathematics',
                'slug' => 'mathematics',
                'icon' => '🔢',
                'sort_order' => 1,
                'seo_content' => 'Find expert Mathematics tutors in Bangladesh. Get help with algebra, calculus, geometry, trigonometry, and more. Our verified math tutors provide personalized lessons for all levels.',
                'meta_title' => 'Mathematics Tutors in Bangladesh | Expert Math Home Tutors',
                'meta_description' => 'Find qualified Mathematics tutors for all levels. Expert help in algebra, calculus, geometry. 500+ verified math tutors available.',
            ],
            [
                'name' => 'English',
                'slug' => 'english',
                'icon' => '📚',
                'sort_order' => 2,
                'seo_content' => 'Looking for English tutors? Find experienced English language tutors for grammar, writing, literature, and speaking skills. Improve your English with qualified teachers.',
                'meta_title' => 'English Tutors in Bangladesh | IELTS, Spoken English Teachers',
                'meta_description' => 'Find qualified English tutors for grammar, writing, IELTS, spoken English. Native and experienced teachers available.',
            ],
            [
                'name' => 'Physics',
                'slug' => 'physics',
                'icon' => '⚛️',
                'sort_order' => 3,
                'seo_content' => 'Get expert Physics tuition from qualified teachers. Topics include mechanics, electricity, magnetism, optics, thermodynamics, and modern physics for O Level, A Level, and university students.',
                'meta_title' => 'Physics Tutors in Bangladesh | O Level, A Level Physics Teachers',
                'meta_description' => 'Find expert Physics tutors for O Level, A Level, and university. Mechanics, electricity, modern physics covered.',
            ],
            [
                'name' => 'Chemistry',
                'slug' => 'chemistry',
                'icon' => '🧪',
                'sort_order' => 4,
                'seo_content' => 'Find experienced Chemistry tutors for organic, inorganic, and physical chemistry. Expert guidance for O Level, A Level, and higher studies with practical demonstrations.',
                'meta_title' => 'Chemistry Tutors in Bangladesh | Organic, Inorganic Chemistry',
                'meta_description' => 'Expert Chemistry tutors for all levels. Organic, inorganic, physical chemistry covered by qualified teachers.',
            ],
            [
                'name' => 'Biology',
                'slug' => 'biology',
                'icon' => '🧬',
                'sort_order' => 5,
                'seo_content' => 'Looking for Biology tutors? Find qualified teachers for botany, zoology, human biology, genetics, and ecology. Perfect for O Level, A Level, and medical entrance preparation.',
                'meta_title' => 'Biology Tutors in Bangladesh | Botany, Zoology, Medical Entrance',
                'meta_description' => 'Find experienced Biology tutors for O Level, A Level, and medical entrance. Expert guidance in all topics.',
            ],
            [
                'name' => 'Bangla',
                'slug' => 'bangla',
                'icon' => '📖',
                'sort_order' => 6,
                'seo_content' => 'Find qualified Bangla tutors for literature, grammar, and composition. Expert teachers for Bengali language learning at all educational levels.',
                'meta_title' => 'Bangla Tutors in Bangladesh | Bengali Literature, Grammar Teachers',
                'meta_description' => 'Experienced Bangla tutors for literature, grammar, composition. Qualified teachers for all levels.',
            ],
            [
                'name' => 'ICT',
                'slug' => 'ict',
                'icon' => '💻',
                'sort_order' => 7,
                'seo_content' => 'Need ICT tutors? Get expert guidance in Information and Communication Technology, programming, web development, and computer science from experienced teachers.',
                'meta_title' => 'ICT Tutors in Bangladesh | Computer Science, Programming Teachers',
                'meta_description' => 'Find qualified ICT tutors for programming, computer science, O Level, A Level. Expert tech teachers.',
            ],
            [
                'name' => 'Accounting',
                'slug' => 'accounting',
                'icon' => '💰',
                'sort_order' => 8,
                'seo_content' => 'Looking for Accounting tutors? Find qualified teachers for financial accounting, cost accounting, management accounting, and commerce subjects.',
                'meta_title' => 'Accounting Tutors in Bangladesh | Commerce, Finance Teachers',
                'meta_description' => 'Expert Accounting tutors for O Level, A Level, Bachelor. Financial, cost, management accounting covered.',
            ],
            [
                'name' => 'Business Studies',
                'slug' => 'business-studies',
                'icon' => '💼',
                'sort_order' => 9,
                'seo_content' => 'Find experienced Business Studies tutors for marketing, management, entrepreneurship, and business concepts. Qualified teachers for all levels.',
                'meta_title' => 'Business Studies Tutors in Bangladesh | Marketing, Management',
                'meta_description' => 'Qualified Business Studies tutors for O Level, A Level. Marketing, management, entrepreneurship covered.',
            ],
            [
                'name' => 'Economics',
                'slug' => 'economics',
                'icon' => '📊',
                'sort_order' => 10,
                'seo_content' => 'Get expert Economics tuition covering microeconomics, macroeconomics, development economics, and econometrics from qualified teachers.',
                'meta_title' => 'Economics Tutors in Bangladesh | Micro, Macro Economics Teachers',
                'meta_description' => 'Find experienced Economics tutors for all levels. Microeconomics, macroeconomics, development covered.',
            ],
            [
                'name' => 'Higher Math',
                'slug' => 'higher-math',
                'icon' => '📐',
                'sort_order' => 11,
                'seo_content' => 'Higher Mathematics tutors for SSC and HSC science students: algebra, trigonometry, vectors, calculus and coordinate geometry.',
                'meta_title' => 'Higher Math Tutors in Dhaka | SSC and HSC',
                'meta_description' => 'Find Higher Math tutors for SSC and HSC: algebra, trigonometry, calculus and geometry.',
            ],
            [
                'name' => 'General Science',
                'slug' => 'general-science',
                'icon' => '🔬',
                'sort_order' => 12,
                'seo_content' => 'General Science tutors for primary and junior classes, covering the national curriculum science book.',
                'meta_title' => 'General Science Tutors in Dhaka | Class 1-8',
                'meta_description' => 'Find General Science tutors for Class 1 to 8 in Dhaka.',
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(
                ['slug' => $subject['slug']],
                $subject
            );
        }

        $this->command->info('✓ ' . count($subjects) . ' subjects created successfully!');
    }
}
