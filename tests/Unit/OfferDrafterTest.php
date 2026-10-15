<?php

namespace Tests\Unit;

use App\Offers\OfferDrafter;
use App\Support\TuitionCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Sleep;
use Tests\TestCase;

class OfferDrafterTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function aiReply(array $override = []): array
    {
        return $override + [
            'title' => 'Mathematics tutor for Class 9', 'description' => 'A Class 9 student needs Mathematics.',
            'student_class' => 'Class 9', 'subjects' => ['Mathematics'], 'area' => 'Uttara', 'tuition_type' => 'home',
            'tutor_gender_preference' => 'any', 'days_per_week' => 3, 'salary' => 8000, 'location' => 'Sector 7, Uttara',
        ];
    }

    public function test_uses_the_model_answer_mapped_to_known_ids(): void
    {
        $this->fakeAi([$this->aiReply()]);

        $draft = app(OfferDrafter::class)->draft('class 9 er jonno math tutor, uttara sector 7, 3 din, 8000');

        $this->assertSame('ai', $draft->source);
        $this->assertSame('Uttara', TuitionCatalog::areas()[$draft->area_id]);
        $this->assertSame(TuitionCatalog::categoryIdFor('Class 9'), $draft->category_id);
        $this->assertSame('Sector 7, Uttara', $draft->location);
        $this->assertSame(1, DB::table('ai_calls')->where('feature', 'offer_intake')->where('ok', true)->count());
    }

    public function test_values_outside_the_allowlists_are_dropped(): void
    {
        $this->fakeAi([$this->aiReply([
            'student_class' => 'Grade 13', 'subjects' => ['Mathematics', 'Astrology'], 'area' => 'Chittagong',
            'tuition_type' => 'teleport', 'tutor_gender_preference' => 'robot', 'days_per_week' => 12, 'salary' => 1,
            'title' => '<script>alert(1)</script>Tutor',
        ])]);

        $draft = app(OfferDrafter::class)->draft('Need a tutor', backfill: false);

        $this->assertNull($draft->student_class);
        $this->assertSame(['Mathematics'], $draft->subjects);
        $this->assertNull($draft->area_id);
        $this->assertSame('home', $draft->tuition_type);
        $this->assertSame('any', $draft->tutor_gender_preference);
        $this->assertNull($draft->days_per_week);
        $this->assertNull($draft->salary);
        $this->assertStringNotContainsString('<script>', $draft->title);
    }

    public function test_gaps_in_the_model_answer_are_filled_from_the_rules(): void
    {
        $this->fakeAi([$this->aiReply(['salary' => 0, 'area' => ''])]);

        $draft = app(OfferDrafter::class)->draft('Class 9 math tutor in Mirpur, 3 days, budget 7500 tk');

        $this->assertSame('ai+rules', $draft->source);
        $this->assertSame(7500, $draft->salary);
        $this->assertSame('Mirpur', TuitionCatalog::areas()[$draft->area_id]);
    }

    public function test_an_explicit_gender_from_the_rules_beats_the_models_default(): void
    {
        $this->fakeAi([$this->aiReply(['tutor_gender_preference' => 'any'])]);

        $draft = app(OfferDrafter::class)->draft('Badda te HSC accounting er jonno sir lagbe, saptahe 4 din, 9 hajar');

        $this->assertSame('male', $draft->tutor_gender_preference);
        $this->assertSame('ai+rules', $draft->source);
    }

    public function test_a_provider_failure_falls_back_to_the_rules(): void
    {
        Sleep::fake();
        $this->fakeAi([503, 503, 503]);

        $draft = app(OfferDrafter::class)->draft('Class 8 English tutor in Banani, 6000 taka');

        $this->assertSame('rules', $draft->source);
        $this->assertSame('Class 8', $draft->student_class);
        $this->assertSame(1, DB::table('ai_calls')->where('ok', false)->count());
    }

    public function test_without_ai_the_rules_answer(): void
    {
        $draft = app(OfferDrafter::class)->draft('HSC chemistry tutor, Uttara, 4 days, 12000 tk');

        $this->assertSame('rules', $draft->source);
        $this->assertSame(['Chemistry'], $draft->subjects);
        $this->assertSame(0, DB::table('ai_calls')->count());
    }
}
