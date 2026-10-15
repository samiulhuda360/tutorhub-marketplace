<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Area;
use App\Models\Commission;
use App\Models\Invitation;
use App\Models\TuitionOffer;
use App\Models\TutorProfile;
use App\Models\User;
use App\Support\TuitionCatalog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Demo data: 22 tutors, 6 parents, 14 offers, applications and invitations at every
 * stage of the match flow, and one recorded commission. Deterministic, so screenshots
 * and tests see the same data every time. All people are made up.
 *
 * Two tutors are deliberately suspicious (shared phone, copied bio and cover letter,
 * impossible experience) so the admin's review page has something to show.
 */
class DemoSeeder extends Seeder
{
    private const TUTORS = [
        // name, gender, education, years, subjects, classes, areas, fee, verified, age, bio
        ['Nusrat Jahan', 'female', 'BSc in Mathematics, University of Dhaka', 6, ['Mathematics', 'Higher Math', 'Physics'], ['Class 9', 'Class 10', 'SSC'], ['Mirpur', 'Kalabagan'], 9000, true, 28,
            'I teach Mathematics, Higher Math and Physics for Class 9, Class 10 and SSC. I work through every chapter with solved examples first, then board-style questions, and I give a short weekly test so parents can see progress.'],
        ['Tanvir Ahmed', 'male', 'BSc in Electrical and Electronic Engineering, BUET', 4, ['Physics', 'Mathematics', 'Higher Math'], ['SSC', 'HSC'], ['Dhanmondi', 'Lalmatia', 'Mohammadpur'], 12000, true, 25,
            'Engineering student who enjoys making Physics intuitive. For SSC and HSC students I connect formulas to real experiments, then drill the creative questions that carry the most marks.'],
        ['Farhana Islam', 'female', 'BA in English, University of Dhaka', 5, ['English', 'Bangla'], ['Class 6', 'Class 7', 'Class 8'], ['Mirpur', 'Mohammadpur'], 7000, true, 27,
            'I help junior students read, write and speak with confidence in English and Bangla. Grammar is taught through short daily exercises and every week the student writes one composition that we correct together.'],
        ['Rahim Uddin', 'male', 'MSc in Chemistry, Jahangirnagar University', 8, ['Chemistry', 'Biology'], ['HSC'], ['Uttara'], 14000, true, 32,
            'HSC Chemistry and Biology specialist. I prepare chapter notes, focus on reactions and diagrams that appear every year, and run full model tests in the last two months before the exam.'],
        ['Ayesha Siddiqua', 'female', 'MBBS (5th year), Dhaka Medical College', 3, ['Biology', 'Chemistry'], ['SSC', 'HSC'], ['Dhanmondi', 'Green Road', 'Kalabagan'], 13000, false, 24,
            'Medical student teaching Biology and Chemistry for SSC and HSC. I use diagrams and memory techniques for the long Biology chapters and keep a list of every mistake the student makes to revise before tests.'],
        ['Shakib Hasan', 'male', 'BBA, Institute of Business Administration, University of Dhaka', 3, ['Accounting', 'Business Studies', 'Economics'], ['HSC', 'A Level'], ['Gulshan', 'Banani'], 15000, true, 24,
            'Business graduate teaching Accounting, Business Studies and Economics. I explain accounting entries with simple shop examples and practise past papers under time limits for HSC and A Level.'],
        ['Nabila Khan', 'female', 'BSc in Computer Science, North South University', 2, ['ICT', 'Mathematics'], ['Class 8', 'Class 9', 'Class 10'], ['Bashundhara', 'Badda'], 8000, false, 23,
            'Computer science graduate who teaches ICT with hands-on practice: HTML, number systems and programming basics on a laptop, plus Mathematics for Class 8 to 10.'],
        ['Imran Hossain', 'male', 'BSc in Economics, North South University', 6, ['Economics', 'Mathematics'], ['O Level', 'A Level'], ['Gulshan', 'Banani', 'Bashundhara'], 20000, true, 29,
            'Cambridge O and A Level tutor for Economics and Mathematics. I follow the syllabus and mark schemes closely, and students practise structured answers on past papers every week.'],
        ['Sumaiya Akter', 'female', 'BSc in Botany, University of Dhaka', 2, ['General Science', 'Bangla', 'English', 'Mathematics'], ['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5'], ['Mirpur', 'Mohammadpur'], 5000, true, 23,
            'Patient tutor for primary students. I cover all subjects from the school book, help with homework every day and use drawings and small games so young children stay interested.'],
        ['Mehedi Hasan', 'male', 'BSc in Mathematics, Jagannath University', 7, ['Mathematics', 'Higher Math'], ['SSC', 'HSC', 'Admission Test'], ['Motijheel', 'Shantinagar', 'Malibagh'], 11000, true, 30,
            'Mathematics and Higher Math for SSC, HSC and university admission tests. I teach short methods for MCQ, then full written solutions, and track every chapter on a checklist with the student.'],
        ['Tasnim Rahman', 'female', 'BA in English Literature, ULAB', 4, ['English'], ['O Level', 'A Level'], ['Dhanmondi', 'Gulshan'], 16000, true, 26,
            'English Language and Literature for O and A Level. I focus on essay structure, comprehension technique and wide reading, with one timed paper every two weeks. Online classes are fine too.'],
        ['Arif Chowdhury', 'male', 'MSc in Physics, University of Dhaka', 10, ['Physics', 'Higher Math'], ['HSC', 'Admission Test'], ['Farmgate', 'Tejgaon', 'Mohakhali'], 15000, true, 34,
            'HSC Physics and Higher Math, and engineering and medical admission preparation. Ten years of teaching: I know which topics need extra time and I give a weekly problem set with solutions.'],
        ['Maliha Haque', 'female', 'BSc in Chemistry, University of Dhaka', 3, ['Chemistry', 'Mathematics'], ['Class 9', 'Class 10', 'SSC'], ['Uttara'], 9000, false, 24,
            'I teach Chemistry and Mathematics for Class 9, Class 10 and SSC in Uttara. Every topic ends with a short quiz, and I share a summary sheet the student can revise from before exams.'],
        ['Sabbir Ahmed', 'male', 'BSc in Civil Engineering, RUET', 1, ['Mathematics', 'Physics'], ['Class 6', 'Class 7', 'Class 8'], ['Rampura', 'Badda', 'Malibagh'], 6000, false, 23,
            'Recent engineering graduate teaching Mathematics and Physics for Class 6 to 8. I keep classes practical and make sure the basics are solid before moving on.'],
        ['Rumana Afroz', 'female', 'MA in Bangla, University of Dhaka', 9, ['Bangla', 'English'], ['Class 9', 'Class 10', 'SSC'], ['Shantinagar', 'Paltan', 'Kakrail'], 8000, true, 35,
            'Bangla and English for Class 9, Class 10 and SSC. I teach grammar rules with examples from the textbook and practise letters, applications and essays the way examiners mark them.'],
        ['Zahid Hasan', 'male', 'BSc in Pharmacy, University of Dhaka', 2, ['Biology', 'Chemistry'], ['Class 9', 'Class 10'], ['Mohakhali', 'Banani'], 8000, false, 24,
            'Pharmacy student teaching Biology and Chemistry for Class 9 and 10. I explain with labelled diagrams and simple experiments at home where possible.'],
        ['Kaniz Fatema', 'female', 'BSc in Mathematics, Eden Mohila College', 5, ['Mathematics', 'General Science'], ['Class 3', 'Class 4', 'Class 5', 'Class 6'], ['Lalmatia', 'Mohammadpur'], 6000, true, 27,
            'Mathematics and General Science for Class 3 to 6. I make sure children understand each step instead of memorising, and I keep parents updated after every week.'],
        ['Fahim Rahman', 'male', 'BSc in Computer Science and Engineering, BRAC University', 3, ['ICT', 'Mathematics', 'Physics'], ['HSC', 'O Level'], ['Gulshan', 'Mohakhali'], 12000, true, 25,
            'ICT, Mathematics and Physics for HSC and O Level. For ICT I teach programming in C and database basics with exercises on a computer; for Physics, problem solving step by step.'],
        ['Jannatul Ferdous', 'female', 'BBA in Accounting, University of Dhaka', 4, ['Accounting', 'Business Studies'], ['SSC', 'HSC'], ['Motijheel', 'Paltan'], 9000, true, 26,
            'Accounting and Business Studies for SSC and HSC. Journal, ledger and final accounts are practised until they are automatic, and I mark every exercise the same day.'],
        ['Rakib Hasan', 'male', 'BSc in Statistics, University of Dhaka (2nd year)', 1, ['Mathematics', 'English'], ['Class 4', 'Class 5', 'Class 6', 'Class 7'], ['Badda', 'Rampura'], 4500, false, 21,
            'University student teaching Mathematics and English for Class 4 to 7. I help with homework, school tests and building good study habits.'],
        // The two suspicious profiles (see ProfileReview).
        ['Rafiq Islam', 'male', 'BSc in Mathematics, National University', 3, ['Mathematics', 'Physics'], ['Class 9', 'Class 10', 'SSC'], ['Mirpur'], 7000, false, 25,
            'I teach Mathematics, Higher Math and Physics for Class 9, Class 10 and SSC. I work through every chapter with solved examples first, then board-style questions, and I give a short weekly test so parents can see progress.'],
        ['Rafsan Kabir', 'male', 'BSc in Physics, National University', 12, ['Physics', 'Mathematics'], ['SSC', 'HSC'], ['Mirpur', 'Mohammadpur'], 7500, false, 24,
            'Physics and Mathematics tutor for SSC and HSC students in Mirpur and Mohammadpur.'],
    ];

    private const PARENTS = ['Mahmuda Begum', 'Kamal Hossain', 'Shirin Akter', 'Tariq Rahman', 'Farzana Yasmin', 'Abdul Karim'];

    private const OFFERS = [
        // parent, title, description, class, subjects, area, gender, salary, type, days, days ago, status
        [0, 'Mathematics and Physics tutor for Class 10', 'My daughter is in Class 10 at a Mirpur school and finds Physics difficult. We want someone who explains concepts clearly and gives regular tests before the SSC test exam.', 'Class 10', ['Mathematics', 'Physics'], 'Mirpur', 'female', 10000, 'home', 4, 1, 'open'],
        [1, 'English and Bangla tutor for Class 8', 'Looking for a patient tutor for my son in Class 8. He needs help with English grammar and Bangla second paper. Evenings after 5 pm.', 'Class 8', ['English', 'Bangla'], 'Mohammadpur', 'any', 7000, 'home', 3, 2, 'open'],
        [2, 'SSC science tutor: Physics, Chemistry, Higher Math', 'SSC candidate in the science group needs help with Physics, Chemistry and Higher Math. Must have experience with board exam preparation.', 'SSC', ['Physics', 'Chemistry', 'Higher Math'], 'Dhanmondi', 'any', 15000, 'home', 5, 2, 'open'],
        [3, 'Chemistry and Biology tutor for HSC (female)', 'My daughter is in HSC first year, science group. She needs Chemistry and Biology with good notes. Female tutor preferred.', 'HSC', ['Chemistry', 'Biology'], 'Uttara', 'female', 13000, 'home', 4, 3, 'open'],
        [4, 'Online O Level Mathematics tutor', 'O Level student (Cambridge, Year 10) needs Mathematics classes online on Zoom, three evenings a week. Experience with past papers needed.', 'O Level', ['Mathematics'], 'Gulshan', 'any', 12000, 'online', 3, 4, 'open'],
        [5, 'A Level Economics tutor in Gulshan', 'A Level student needs help with Economics essays and data response questions for the May/June exams.', 'A Level', ['Economics'], 'Gulshan', 'any', 18000, 'home', 2, 5, 'open'],
        [0, 'Tutor for Class 4: all subjects', 'Need a caring tutor for my younger son in Class 4 for all subjects: Mathematics, English, Bangla and General Science, and daily homework.', 'Class 4', ['Mathematics', 'English', 'Bangla', 'General Science'], 'Mirpur', 'female', 5000, 'home', 5, 6, 'open'],
        [1, 'HSC Accounting tutor', 'HSC business studies student needs help with Accounting, especially final accounts.', 'HSC', ['Accounting', 'Business Studies'], 'Motijheel', 'any', 9000, 'home', 3, 6, 'open'],
        [2, 'Engineering admission: Physics and Higher Math', 'HSC passed student preparing for BUET and engineering admission tests. Need intensive Physics and Higher Math practice.', 'Admission Test', ['Physics', 'Higher Math'], 'Farmgate', 'male', 16000, 'home', 4, 7, 'open'],
        [3, 'ICT and Mathematics for Class 9', 'Class 9 student needs ICT practical help and Mathematics. Laptop available at home.', 'Class 9', ['ICT', 'Mathematics'], 'Bashundhara', 'any', 8000, 'home', 3, 8, 'open'],
        [4, 'Class 6 Mathematics tutor', 'Class 6 student is weak in Mathematics and needs someone to build the basics.', 'Class 6', ['Mathematics'], 'Rampura', 'any', 5000, 'home', 3, 9, 'open'],
        [5, 'HSC Physics and Higher Math (male tutor)', 'HSC second year student needs Physics and Higher Math before the test exam. Male tutor preferred.', 'HSC', ['Physics', 'Higher Math'], 'Dhanmondi', 'male', 14000, 'home', 4, 10, 'open'],
        [2, 'O Level English Language tutor', 'Year 9 O Level student needs English Language: essays and comprehension.', 'O Level', ['English'], 'Gulshan', 'female', 15000, 'home', 2, 12, 'open'],
        [1, 'Class 7 Bangla and English tutor', 'Filled through TutorHub.', 'Class 7', ['Bangla', 'English'], 'Shantinagar', 'any', 6000, 'home', 3, 30, 'filled'],
    ];

    public function run(): void
    {
        $area = Area::pluck('id', 'name');
        $tutors = [];
        foreach (self::TUTORS as $i => [$name, $gender, $education, $years, $subjects, $classes, $areas, $fee, $verified, $age, $bio]) {
            $user = User::create([
                'name' => $name, 'email' => 'tutor'.($i + 1).'@example.com', 'password' => Hash::make('password'),
                'role' => 'tutor', 'email_verified_at' => now(),
            ]);
            // Demo numbers use the 010 prefix, which no Bangladeshi operator issues.
            $phone = match ($name) {
                'Rafiq Islam', 'Rafsan Kabir' => '01000000222',
                default => '010'.str_pad((string) (10000000 + $i * 731), 8, '0', STR_PAD_LEFT),
            };
            TutorProfile::create([
                'user_id' => $user->id, 'phone' => $phone, 'gender' => $gender,
                'date_of_birth' => now()->subYears($age)->subMonths($i % 11)->toDateString(),
                'education' => $education, 'experience_years' => $years, 'bio' => $bio,
                'subjects' => $subjects, 'classes' => $classes,
                'areas' => array_values(array_map(fn ($a) => $area[$a], $areas)),
                'hourly_rate' => $fee, 'availability' => $i % 2 ? 'Saturday to Thursday, 4 pm to 9 pm' : 'Every day after 3 pm, Friday morning',
                'is_verified' => $verified, 'is_active' => true,
                'tuition_types' => in_array($name, ['Imran Hossain', 'Tasnim Rahman', 'Fahim Rahman', 'Mehedi Hasan'], true) ? ['home', 'online'] : ['home'],
            ]);
            $tutors[$name] = $user;
        }

        $parents = [];
        foreach (self::PARENTS as $i => $name) {
            $parents[] = User::create([
                'name' => $name, 'email' => 'parent'.($i + 1).'@example.com', 'password' => Hash::make('password'),
                'role' => 'parent', 'email_verified_at' => now(),
            ]);
        }

        $offers = [];
        foreach (self::OFFERS as [$parent, $title, $description, $class, $subjects, $areaName, $gender, $salary, $type, $days, $ago, $status]) {
            $offers[] = TuitionOffer::create([
                'user_id' => $parents[$parent]->id, 'category_id' => TuitionCatalog::categoryIdFor($class),
                'area_id' => $area[$areaName], 'title' => $title, 'description' => $description,
                'student_class' => $class, 'subjects' => $subjects, 'tutor_gender_preference' => $gender,
                'salary' => $salary, 'tuition_type' => $type, 'days_per_week' => $days,
                'location' => 'House '.(10 + $ago).', Road '.(3 + $days).', '.$areaName, 'status' => $status,
                'posted_at' => now()->subDays($ago),
            ]);
        }

        // Each demo tutor writes their own letter, from their own profile.
        $letter = function (string $tutor, int $offer) use ($offers, $tutors) {
            $p = $tutors[$tutor]->tutorProfile;
            $o = $offers[$offer];

            return 'Hello, I am '.$tutor.' ('.$p->education.'). '.strtok($p->bio, '.').'. For your '.$o->student_class
                .' student I would start with a short test to see where the gaps are in '.implode(' and ', $o->subjects)
                .', then follow a weekly plan. '.$p->experience_years.' years of tutoring so far. '
                .($o->days_per_week <= 3 ? 'Evenings suit me.' : 'I can manage '.$o->days_per_week.' days a week.');
        };
        $copied = 'Dear guardian, I am very interested in your tuition. I have good experience and my students always get good results. '
            .'I am punctual and friendly. Please give me a chance, I will not disappoint you. Thank you.';

        $apply = function (string $tutor, int $offer, string $status, int $ago, array $extra = [], ?string $text = null) use ($tutors, $offers, $letter) {
            return Application::create([
                'tuition_offer_id' => $offers[$offer]->id, 'tutor_id' => $tutors[$tutor]->id,
                'cover_letter' => $text ?? $letter($tutor, $offer), 'status' => $status, 'applied_at' => now()->subDays($ago),
            ] + $extra);
        };

        $apply('Nusrat Jahan', 0, 'pending', 0);
        $apply('Maliha Haque', 0, 'pending', 0);
        $apply('Rafiq Islam', 0, 'pending', 1, [], $copied);
        $apply('Farhana Islam', 1, 'accepted', 1, ['sent_to_admin_at' => now()->subHours(20)]);
        $apply('Tanvir Ahmed', 2, 'accepted', 1, ['sent_to_admin_at' => now()->subHours(30), 'admin_contacted_at' => now()->subHours(6)]);
        $apply('Rafsan Kabir', 2, 'pending', 2, [], $copied);
        $apply('Rahim Uddin', 3, 'rejected', 2);
        $apply('Ayesha Siddiqua', 3, 'pending', 2);
        $apply('Imran Hossain', 4, 'pending', 3);
        $apply('Shakib Hasan', 5, 'pending', 4);
        $apply('Jannatul Ferdous', 7, 'pending', 5);
        $apply('Arif Chowdhury', 8, 'pending', 6);

        // A finished match: accepted, contacted, commission paid, contact shared.
        $done = $apply('Rumana Afroz', 13, 'accepted', 25, [
            'sent_to_admin_at' => now()->subDays(24), 'admin_contacted_at' => now()->subDays(23),
        ]);
        $commission = Commission::create([
            'match_type' => 'application', 'match_id' => $done->id, 'tutor_id' => $done->tutor_id,
            'parent_id' => $offers[13]->user_id, 'amount' => 3000, 'payment_method' => 'bkash',
            'receipt_number' => 'BK-DEMO-0001', 'paid_at' => now()->subDays(22),
        ]);
        $done->update(['commission_id' => $commission->id, 'parent_contact_shared_at' => now()->subDays(22)]);

        $invite = fn (int $parent, string $tutor, ?int $offer, string $status, array $extra = []) => Invitation::create([
            'parent_id' => $parents[$parent]->id, 'tutor_id' => $tutors[$tutor]->id,
            'tuition_offer_id' => $offer !== null ? $offers[$offer]->id : null,
            'message' => 'Hello, we saw your profile on TutorHub and think you could be a good fit for our child. Would you be interested?',
            'status' => $status,
        ] + $extra);
        $invite(0, 'Sumaiya Akter', 6, 'pending');
        $invite(4, 'Sabbir Ahmed', 10, 'with_admin', ['accepted_at' => now()->subDays(2), 'sent_to_admin_at' => now()->subDays(2), 'tutor_response_message' => 'Yes, I can start next week.']);
        $invite(3, 'Nabila Khan', 9, 'negotiating', ['accepted_at' => now()->subDays(4), 'sent_to_admin_at' => now()->subDays(4)]);
        $invite(5, 'Mehedi Hasan', 11, 'rejected', ['rejected_at' => now()->subDays(3), 'tutor_response_message' => 'Sorry, my schedule is full this month.']);
    }
}
