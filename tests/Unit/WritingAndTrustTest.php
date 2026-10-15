<?php

namespace Tests\Unit;

use App\Ai\Embeddings;
use App\Ai\ProfileWriter;
use App\Models\TuitionOffer;
use App\Trust\ProfileReview;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WritingAndTrustTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_grounding_guard_rejects_numbers_the_facts_do_not_have(): void
    {
        $facts = "Years teaching: 4\nClasses: Class 9, Class 10";

        $this->assertTrue(ProfileWriter::grounded('I have 4 years of experience with Class 9 and 10.', $facts));
        $this->assertFalse(ProfileWriter::grounded('I have 12 years of experience and 98% of my students got A+.', $facts));
    }

    public function test_an_ai_draft_with_invented_numbers_is_replaced_by_the_template(): void
    {
        $this->fakeAi(['I have taught for 15 years and 200 students got GPA 5.']);

        $out = app(ProfileWriter::class)->bio(['education' => 'BSc in Physics', 'experience_years' => 3, 'subjects' => ['Physics'], 'classes' => ['SSC']]);

        $this->assertSame('rules', $out['source']);
        $this->assertStringContainsString('3 years', $out['text']);
    }

    public function test_a_grounded_ai_cover_letter_is_used(): void
    {
        $offer = TuitionOffer::where('title', 'HSC Accounting tutor')->firstOrFail();
        $letter = 'Hello. I teach Accounting and Business Studies for HSC students and studied accounting at university. I can come 3 days a week and will start with final accounts, which your child finds hard.';
        $this->fakeAi([$letter]);

        $out = app(ProfileWriter::class)->coverLetter($offer, $this->tutor('Jannatul Ferdous')->tutorProfile->toArray());

        $this->assertSame(['text' => $letter, 'source' => 'ai'], $out);
    }

    public function test_lexical_vectors_put_similar_texts_closer(): void
    {
        $a = Embeddings::lexical('HSC Physics and Higher Math tutor for admission tests');
        $b = Embeddings::lexical('Physics and Higher Math for HSC and admission');
        $c = Embeddings::lexical('Primary school Bangla and General Science homework help');

        $this->assertGreaterThan(Embeddings::cosine($a, $c), Embeddings::cosine($a, $b));
    }

    public function test_review_flags_the_planted_suspicious_profiles(): void
    {
        $flagged = app(ProfileReview::class)->flagged()->mapWithKeys(fn ($row) => [$row['profile']->user->name => $row['signals']]);

        $this->assertContains('Same phone number as Rafsan Kabir', $flagged['Rafiq Islam']);
        $this->assertContains("Bio copies Nusrat Jahan's (an older profile)", $flagged['Rafiq Islam']);
        $this->assertContains('Bio was copied by Rafiq Islam', $flagged['Nusrat Jahan']);
        $this->assertContains('Sent the same cover letter as Rafiq Islam', $flagged['Rafsan Kabir']);
        $this->assertStringContainsString('12 years of experience at age', implode(' ', $flagged['Rafsan Kabir']));
        $this->assertArrayNotHasKey('Tanvir Ahmed', $flagged->all());
        $this->assertArrayNotHasKey('Maliha Haque', $flagged->all()); // honest letters to the same job are not copies
    }
}
