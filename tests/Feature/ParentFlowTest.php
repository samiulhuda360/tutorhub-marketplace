<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Area;
use App\Models\Invitation;
use App\Models\TuitionOffer;
use App\Support\TuitionCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParentFlowTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function validOffer(array $override = []): array
    {
        return $override + [
            'category_id' => TuitionCatalog::categoryIdFor('Class 9'), 'area_id' => Area::where('name', 'Uttara')->value('id'),
            'title' => 'Mathematics tutor for Class 9', 'description' => 'Class 9 student needs Mathematics.',
            'student_class' => 'Class 9', 'subjects' => ['Mathematics'], 'tutor_gender_preference' => 'any',
            'salary' => 8000, 'tuition_type' => 'home', 'days_per_week' => 3, 'location' => 'Sector 7, Uttara',
        ];
    }

    public function test_parent_posts_an_offer_and_sees_matching_tutors(): void
    {
        $this->actingAs($this->parentUser())->post('/parent/offers', $this->validOffer())->assertRedirect();
        $offer = TuitionOffer::latest('id')->first();

        $this->get('/parent/offers/'.$offer->id)->assertOk()
            ->assertSee('Tutors who fit this offer')
            ->assertSee('Maliha Haque')   // teaches Mathematics for Class 9 in Uttara
            ->assertSee('Rule-based ranking');
    }

    public function test_unknown_subjects_and_classes_are_rejected(): void
    {
        $this->actingAs($this->parentUser())
            ->post('/parent/offers', $this->validOffer(['subjects' => ['Astrology'], 'student_class' => 'Grade 13']))
            ->assertSessionHasErrors(['subjects.0', 'student_class']);
    }

    public function test_offer_draft_endpoint_returns_a_structured_offer(): void
    {
        $this->actingAs($this->parentUser())
            ->postJson('/parent/offers/draft', ['request' => 'ক্লাস ৮ এর জন্য গণিত শিক্ষক দরকার, মিরপুর, সপ্তাহে ৩ দিন, বেতন ৮০০০ টাকা'])
            ->assertOk()
            ->assertJson(['student_class' => 'Class 8', 'subjects' => ['Mathematics'], 'days_per_week' => 3, 'salary' => 8000, 'source' => 'rules'])
            ->assertJsonPath('area_id', Area::where('name', 'Mirpur')->value('id'));
    }

    public function test_offer_draft_is_for_parents_only_and_rate_limited(): void
    {
        $this->actingAs($this->tutor('Nusrat Jahan'))->postJson('/parent/offers/draft', ['request' => 'Class 9 math tutor'])->assertForbidden();

        $this->actingAs($this->parentUser(2));
        for ($i = 0; $i < 15; $i++) {
            $this->postJson('/parent/offers/draft', ['request' => 'Class 9 math tutor in Uttara'])->assertOk();
        }
        $this->postJson('/parent/offers/draft', ['request' => 'Class 9 math tutor in Uttara'])->assertStatus(429);
    }

    public function test_parent_sees_tutor_code_not_email_on_applications(): void
    {
        $offer = TuitionOffer::where('title', 'Mathematics and Physics tutor for Class 10')->firstOrFail();
        $nusrat = $this->tutor('Nusrat Jahan');

        $this->actingAs($offer->user)->get('/parent/offers/'.$offer->id)->assertOk()
            ->assertSee($nusrat->tutor_code)
            ->assertDontSee($nusrat->email);
    }

    public function test_accepting_an_application_sends_it_to_the_admin(): void
    {
        $app = Application::where('status', 'pending')->whereHas('tutor', fn ($q) => $q->where('name', 'Nusrat Jahan'))->firstOrFail();

        $this->actingAs($app->tuitionOffer->user)->post('/parent/applications/'.$app->id.'/accept')->assertRedirect();

        $this->assertNotNull($app->fresh()->sent_to_admin_at);
        $this->actingAs($this->admin())->get('/admin/matches')->assertOk()->assertSee('Nusrat Jahan');
    }

    public function test_parent_invites_a_recommended_tutor(): void
    {
        $offer = TuitionOffer::where('title', 'HSC Accounting tutor')->firstOrFail();

        $this->actingAs($offer->user)->post('/parent/invitations', [
            'tutor_id' => $this->tutor('Shakib Hasan')->id, 'tuition_offer_id' => $offer->id, 'message' => 'Would you like to teach our child Accounting?',
        ])->assertRedirect();

        $this->assertTrue(Invitation::where('tuition_offer_id', $offer->id)->where('status', 'pending')->exists());
        $this->get('/parent/offers/'.$offer->id)->assertSee('Invited');
    }

    public function test_parents_cannot_open_other_parents_offers_or_applications(): void
    {
        $offer = TuitionOffer::where('user_id', $this->parentUser(1)->id)->firstOrFail();
        $app = Application::where('tuition_offer_id', $offer->id)->firstOrFail();

        $this->actingAs($this->parentUser(2));
        $this->get('/parent/offers/'.$offer->id)->assertForbidden();
        $this->get('/parent/applications/'.$app->id)->assertForbidden();
    }

    public function test_parent_pages_render(): void
    {
        $parent = $this->parentUser(1);
        $app = Application::whereHas('tuitionOffer', fn ($q) => $q->where('user_id', $parent->id))->firstOrFail();
        $inv = Invitation::where('parent_id', $parent->id)->firstOrFail();

        $this->actingAs($parent);
        foreach (['/parent/dashboard', '/parent/offers', '/parent/offers/create', '/parent/applications',
            '/parent/applications/'.$app->id, '/parent/invitations', '/parent/invitations/'.$inv->id] as $url) {
            $this->get($url)->assertOk();
        }
    }
}
