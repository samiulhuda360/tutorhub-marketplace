<?php

namespace App\Support;

use App\Models\Area;
use App\Models\Category;
use App\Models\Subject;

/**
 * The allowed values for offers and profiles, and the words people use for them
 * (English, Bangla and Banglish). Shared by the offer intake, the matcher and the
 * validators, so a value the AI or a form produces is always one the app knows.
 */
class TuitionCatalog
{
    public const CLASSES = [
        'Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5', 'Class 6', 'Class 7', 'Class 8', 'Class 9',
        'Class 10', 'SSC', 'HSC', 'O Level', 'A Level', 'University', 'Admission Test',
    ];

    public const TYPES = ['home', 'online', 'center'];

    public const GENDERS = ['male', 'female', 'any'];

    /** Classes that are the same stage of study (Class 9-10 sit the SSC, and so on). */
    public const CLASS_GROUPS = [
        ['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5'],
        ['Class 6', 'Class 7', 'Class 8'],
        ['Class 9', 'Class 10', 'SSC'],
        ['HSC'],
        ['O Level'],
        ['A Level'],
        ['University', 'Admission Test'],
    ];

    public const SUBJECT_WORDS = [
        'Mathematics' => ['math', 'maths', 'mathematics', 'অংক', 'অঙ্ক', 'গণিত', 'গনিত', 'ganit', 'ongko'],
        'Higher Math' => ['higher math', 'higher maths', 'উচ্চতর গণিত', 'h.math', 'hmath'],
        'English' => ['english', 'ইংরেজি', 'ইংলিশ', 'ingreji'],
        'Physics' => ['physics', 'পদার্থ', 'পদার্থবিজ্ঞান', 'podartho'],
        'Chemistry' => ['chemistry', 'রসায়ন', 'chem', 'rosayon'],
        'Biology' => ['biology', 'জীববিজ্ঞান', 'bio'],
        'Bangla' => ['bangla', 'bengali', 'বাংলা'],
        'ICT' => ['ict', 'আইসিটি', 'computer'],
        'Accounting' => ['accounting', 'accounts', 'হিসাববিজ্ঞান'],
        'Business Studies' => ['business studies', 'business', 'ব্যবসায়'],
        'Economics' => ['economics', 'অর্থনীতি', 'econ'],
        'General Science' => ['general science', 'সাধারণ বিজ্ঞান'],
    ];

    public const CLASS_WORDS = [
        'SSC' => ['ssc', 'এসএসসি'],
        'HSC' => ['hsc', 'এইচএসসি', 'class 11', 'class 12', 'inter'],
        'O Level' => ['o level', 'o-level', 'olevel', 'ও লেভেল'],
        'A Level' => ['a level', 'a-level', 'alevel', 'এ লেভেল'],
        'University' => ['university', 'honours', 'honors', 'varsity', 'বিশ্ববিদ্যালয়'],
        'Admission Test' => ['admission', 'ভর্তি'],
    ];

    public const ORDINALS = [
        'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5, 'six' => 6, 'seven' => 7, 'eight' => 8,
        'nine' => 9, 'ten' => 10, 'প্রথম' => 1, 'দ্বিতীয়' => 2, 'তৃতীয়' => 3, 'চতুর্থ' => 4, 'পঞ্চম' => 5,
        'ষষ্ঠ' => 6, 'সপ্তম' => 7, 'অষ্টম' => 8, 'নবম' => 9, 'দশম' => 10,
    ];

    public const AREA_WORDS = [
        'Mirpur' => ['মিরপুর'], 'Dhanmondi' => ['ধানমন্ডি', 'dhanmondi'], 'Bashundhara' => ['বসুন্ধরা', 'bashundhara', 'basundhara'],
        'Gulshan' => ['গুলশান'], 'Uttara' => ['উত্তরা'], 'Mohammadpur' => ['মোহাম্মদপুর', 'mohammadpur'], 'Banani' => ['বনানী'],
        'Motijheel' => ['মতিঝিল'], 'Paltan' => ['পল্টন'], 'Kakrail' => ['কাকরাইল'], 'Shantinagar' => ['শান্তিনগর'],
        'Malibagh' => ['মালিবাগ'], 'Rampura' => ['রামপুরা'], 'Badda' => ['বাড্ডা'], 'Mohakhali' => ['মহাখালী'],
        'Farmgate' => ['ফার্মগেট'], 'Tejgaon' => ['তেজগাঁও'], 'Kalabagan' => ['কলাবাগান'], 'Green Road' => ['গ্রিন রোড'],
        'Lalmatia' => ['লালমাটিয়া'],
    ];

    /** @return list<string> */
    public static function subjects(): array
    {
        return Subject::where('is_active', true)->orderBy('sort_order')->pluck('name')->all();
    }

    /** Neighbourhoods an offer can be in (the sub-areas, not the city itself), id => name. */
    public static function areas(): array
    {
        return Area::where('is_active', true)->whereNotNull('parent_id')->orderBy('name')->pluck('name', 'id')->all();
    }

    /** Category for a class: O/A Level are English Medium, SSC/HSC are board exams, the rest is school. */
    public static function categoryIdFor(?string $class): ?int
    {
        $name = match (true) {
            in_array($class, ['O Level', 'A Level'], true) => 'English Medium',
            in_array($class, ['SSC', 'HSC'], true) => 'Board Exam',
            $class !== null => 'School',
            default => null,
        };

        return $name ? Category::where('name', $name)->value('id') : null;
    }

    public static function canonicalClass(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }
        foreach (self::CLASSES as $class) {
            if (strcasecmp(trim($value), $class) === 0) {
                return $class;
            }
        }
        if (preg_match('/^class\s*(\d{1,2})$/i', trim($value), $m) && (int) $m[1] >= 1 && (int) $m[1] <= 10) {
            return 'Class '.(int) $m[1];
        }

        return null;
    }

    /** 1.0 same class or stage, 0.5 the stage next to it, 0 otherwise. */
    public static function classFit(string $wanted, array $taught): float
    {
        if (in_array($wanted, $taught, true)) {
            return 1.0;
        }
        $group = self::groupOf($wanted);
        if ($group !== null && array_intersect(self::CLASS_GROUPS[$group], $taught)) {
            return 1.0;
        }
        foreach ([$group - 1, $group + 1] as $near) {
            if ($group !== null && isset(self::CLASS_GROUPS[$near]) && array_intersect(self::CLASS_GROUPS[$near], $taught)) {
                return 0.5;
            }
        }

        return 0.0;
    }

    /** The classes tutors list for a browse level ("Class 9-10 (SSC)" is Class 9, Class 10 and SSC). */
    public static function classesForLevel(string $level): array
    {
        if (preg_match('/^Class (\d+)-(\d+)/', $level, $m)) {
            $classes = array_map(fn ($n) => $n <= 10 ? 'Class '.$n : null, range((int) $m[1], (int) $m[2]));
            $classes[] = str_contains($level, 'SSC') ? 'SSC' : null;
            $classes[] = str_contains($level, 'HSC') ? 'HSC' : null;

            return array_values(array_filter($classes));
        }

        return match (true) {
            in_array($level, ['O Level', 'A Level', 'Admission Test'], true) => [$level],
            str_contains($level, 'Bachelor'), str_contains($level, 'Masters') => ['University'],
            default => [],
        };
    }

    public static function banglaDigitsToAscii(string $text): string
    {
        return strtr($text, ['০' => '0', '১' => '1', '২' => '2', '৩' => '3', '৪' => '4', '৫' => '5', '৬' => '6', '৭' => '7', '৮' => '8', '৯' => '9']);
    }

    private static function groupOf(string $class): ?int
    {
        foreach (self::CLASS_GROUPS as $i => $group) {
            if (in_array($class, $group, true)) {
                return $i;
            }
        }

        return null;
    }
}
