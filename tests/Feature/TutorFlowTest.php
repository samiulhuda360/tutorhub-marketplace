<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Invitation;
use App\Models\TuitionOffer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TutorFlowTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_tutor_sees_the_job_their_fit_and_applies_once(): void
    {
        $offer = TuitionOffer::where('title', 'HSC Physics and Higher Math (male tutor)')->firstOrFail();
        $arif = $this->tutor('Arif Chowdhury');
        $letter = str_repeat('I teach HSC Physics and Higher Math and can come four days a week. ', 2);

        $this->actingAs($arif)->get('/tutor/offers/'.$offer->id)->assertOk()
            ->assertSee('Apply for this job')->assertSee('How you fit')->assertSee('Draft my message');

        $this->post('/tutor/offers/'.$offer->id.'/apply', ['cover_letter' => $letter])->assertRedirect();
        $this->assertTrue(Application::where('tutor_id', $arif->id)->where('tuition_offer_id', $offer->id)->exists());

        $this->post('/tutor/offers/'.$offer->id.'/apply', ['cover_letter' => $letter])->assertSessionHas('error');
        $this->assertSame(1, Application::where('tutor_id', $arif->id)->where('tuition_offer_id', $offer->id)->count());
    }

    public function test_tutors_with_incomplete_profiles_cannot_apply(): void
    {
        $tutor = User::create(['name' => 'New Tutor', 'email' => 'new@example.com', 'password' => 'password', 'role' => 'tutor']);
        $offer = TuitionOffer::where('status', 'open')->firstOrFail();

        $this->actingAs($tutor)->get('/tutor/offers/'.$offer->id)->assertOk()->assertSee('Complete your profile');
        $this->post('/tutor/offers/'.$offer->id.'/apply', ['cover_letter' => str_repeat('x', 60)])->assertSessionHas('error');
        $this->assertNotNull($tutor->fresh()->tutor_code);
    }

    public function test_draft_endpoints_return_editable_text(): void
    {
        $offer = TuitionOffer::where('title', 'HSC Accounting tutor')->firstOrFail();
        $this->actingAs($this->tutor('Jannatul Ferdous'));

        $this->postJson('/tutor/offers/'.$offer->id.'/cover-letter')->assertOk()
            ->assertJsonPath('source', 'rules')
            ->assertJson(fn ($json) => $json->where('text', fn ($t) => str_contains($t, 'Accounting'))->etc());

        $this->postJson('/tutor/profile/bio-draft', ['education' => 'BBA in Accounting', 'experience_years' => 4,
            'subjects' => ['Accounting'], 'classes' => ['HSC']])->assertOk()->assertJsonPath('source', 'rules');
    }

    public function test_dashboard_lists_jobs_that_fit(): void
    {
        $this->actingAs($this->tutor('Jannatul Ferdous'))->get('/tutor/dashboard')->assertOk()
            ->assertSee('Jobs that fit you')->assertSee('HSC Accounting tutor');
    }

    public function test_parent_contact_stays_hidden_until_the_admin_shares_it(): void
    {
        $pending = Application::whereHas('tutor', fn ($q) => $q->where('name', 'Farhana Islam'))->firstOrFail();
        $done = Application::whereNotNull('parent_contact_shared_at')->firstOrFail();

        $this->actingAs($pending->tutor)->get('/tutor/applications/'.$pending->id)->assertOk()
            ->assertDontSee($pending->tuitionOffer->user->email)
            ->assertDontSee($pending->tuitionOffer->location);
        $this->actingAs($done->tutor)->get('/tutor/applications/'.$done->id)->assertOk()
            ->assertSee($done->tuitionOffer->user->email);
    }

    public function test_tutor_job_list_does_not_show_street_addresses(): void
    {
        $offer = TuitionOffer::where('status', 'open')->firstOrFail();

        $this->actingAs($this->tutor('Nusrat Jahan'))->get('/tutor/offers')->assertOk()->assertDontSee($offer->location);
    }

    public function test_tutor_pages_render(): void
    {
        $tutor = $this->tutor('Sumaiya Akter');
        $inv = Invitation::where('tutor_id', $tutor->id)->firstOrFail();

        $this->actingAs($tutor);
        foreach (['/tutor/dashboard', '/tutor/offers', '/tutor/applications', '/tutor/invitations',
            '/tutor/invitations/'.$inv->id, '/tutor/profile/edit'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_parent_names_cannot_inject_script_into_invitation_buttons(): void
    {
        $inv = Invitation::where('status', 'pending')->firstOrFail();
        $inv->parent->update(['name' => "x');alert(1);//"]);

        $html = $this->actingAs($inv->tutor)->get('/tutor/invitations')->assertOk()->getContent();

        preg_match_all('/onclick="(show(?:Accept|Reject)Modal\([^"]*)"/', $html, $m);
        $this->assertNotEmpty($m[1]);
        foreach ($m[1] as $handler) {
            // What the browser runs: the attribute value with entities decoded.
            $this->assertStringNotContainsString("');alert", html_entity_decode($handler, ENT_QUOTES));
        }
    }
}
