<?php

namespace Tests\Unit;

use App\Matching\TutorMatcher;
use App\Models\TuitionOffer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TutorMatcherTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function offer(string $title): TuitionOffer
    {
        return TuitionOffer::where('title', $title)->firstOrFail();
    }

    public function test_ranks_the_tutor_who_fits_best_first_with_reasons(): void
    {
        $results = app(TutorMatcher::class)->forOffer($this->offer('Mathematics and Physics tutor for Class 10'), 5);

        $top = $results->first();
        $this->assertSame('Nusrat Jahan', $top->tutor->name);
        $this->assertContains('Teaches Class 10 students', $top->reasons);
        $this->assertContains('Works in Mirpur', $top->reasons);
        $this->assertSame($results->pluck('score')->sortDesc()->values()->all(), $results->pluck('score')->all());
    }

    public function test_respects_the_gender_the_parent_asked_for(): void
    {
        $results = app(TutorMatcher::class)->forOffer($this->offer('Mathematics and Physics tutor for Class 10'), 20);

        $this->assertNotEmpty($results);
        $this->assertTrue($results->every(fn ($r) => $r->tutor->tutorProfile->gender === 'female'));
    }

    public function test_every_suggested_tutor_teaches_at_least_one_subject(): void
    {
        $offer = $this->offer('A Level Economics tutor in Gulshan');
        $results = app(TutorMatcher::class)->forOffer($offer, 20);

        $this->assertNotEmpty($results);
        foreach ($results as $r) {
            $this->assertContains('Economics', $r->tutor->tutorProfile->subjects);
        }
    }

    public function test_online_offers_do_not_penalise_location(): void
    {
        $results = app(TutorMatcher::class)->forOffer($this->offer('Online O Level Mathematics tutor'), 20);

        $this->assertTrue($results->every(fn ($r) => $r->part('area')['points'] === 15.0));
    }

    public function test_scores_add_up_to_the_parts(): void
    {
        foreach (app(TutorMatcher::class)->forOffer($this->offer('SSC science tutor: Physics, Chemistry, Higher Math'), 10) as $r) {
            $this->assertSame((int) round(array_sum(array_column($r->parts, 'points'))), $r->score);
            $this->assertLessThanOrEqual(100, $r->score);
        }
    }

    public function test_without_ai_the_explanation_is_built_from_the_reasons(): void
    {
        $results = app(TutorMatcher::class)->forOffer($this->offer('HSC Accounting tutor'), 3, explain: true);

        $this->assertSame('rules', $results->first()->explanationSource);
        $this->assertStringContainsString('Teaches Accounting', $results->first()->explanation);
    }

    public function test_with_ai_each_tutor_gets_a_sentence(): void
    {
        $offer = $this->offer('HSC Accounting tutor');
        $plain = app(TutorMatcher::class)->forOffer($offer, 2);
        $this->fakeAi([['explanations' => $plain->map(fn ($r) => ['tutor_code' => $r->tutor->tutor_code, 'sentence' => 'Fits because of '.$r->tutor->name.'.'])->all()]]);

        $results = app(TutorMatcher::class)->forOffer($offer, 2, explain: true);

        $this->assertSame('ai', $results->first()->explanationSource);
        $this->assertSame('Fits because of '.$results->first()->tutor->name.'.', $results->first()->explanation);
    }

    public function test_answers_that_name_the_tutor_instead_of_the_code_still_match(): void
    {
        $offer = $this->offer('HSC Accounting tutor');
        $plain = app(TutorMatcher::class)->forOffer($offer, 2);
        $this->fakeAi([['explanations' => $plain->map(fn ($r) => ['tutor_code' => strtok($r->tutor->name, ' '), 'sentence' => 'About '.$r->tutor->name.'.'])->all()]]);

        $results = app(TutorMatcher::class)->forOffer($offer, 2, explain: true);

        $this->assertSame('About '.$results->first()->tutor->name.'.', $results->first()->explanation);
    }

    public function test_offers_for_a_tutor_are_ranked_too(): void
    {
        $offers = app(TutorMatcher::class)->forTutor($this->tutor('Jannatul Ferdous'));

        $this->assertSame('HSC Accounting tutor', $offers->first()->offer->title);
    }
}
