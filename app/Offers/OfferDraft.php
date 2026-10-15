<?php

namespace App\Offers;

/** A suggested offer for the parent to review. Nothing is posted until they submit the form. */
final class OfferDraft
{
    public const FIELDS = ['title', 'description', 'student_class', 'subjects', 'area_id', 'category_id',
        'tuition_type', 'tutor_gender_preference', 'days_per_week', 'salary', 'location'];

    /** Fields a parent must fill before posting; any still empty are listed in missing(). */
    private const REQUIRED = ['student_class', 'subjects', 'area_id', 'salary', 'days_per_week', 'location'];

    public function __construct(
        public ?string $title = null,
        public ?string $description = null,
        public ?string $student_class = null,
        public array $subjects = [],
        public ?int $area_id = null,
        public ?int $category_id = null,
        public string $tuition_type = 'home',
        public string $tutor_gender_preference = 'any',
        public ?int $days_per_week = null,
        public ?int $salary = null,
        public ?string $location = null,
        /** 'ai', 'ai+rules' (the model's answer with gaps filled by rules) or 'rules' */
        public string $source = 'rules',
    ) {
    }

    /** @return list<string> */
    public function missing(): array
    {
        return array_values(array_filter(self::REQUIRED, fn ($f) => $this->{$f} === null || $this->{$f} === []));
    }

    public function toArray(): array
    {
        $out = [];
        foreach (self::FIELDS as $f) {
            $out[$f] = $this->{$f};
        }

        return $out + ['source' => $this->source, 'missing' => $this->missing()];
    }
}
