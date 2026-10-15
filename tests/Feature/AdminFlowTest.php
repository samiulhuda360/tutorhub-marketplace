<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Commission;
use App\Models\TutorProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminFlowTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_admin_login_does_not_reveal_which_emails_exist(): void
    {
        $unknown = $this->post('/admin/login', ['email' => 'nobody@example.com', 'password' => 'whatever1'])->assertSessionHasErrors('email');
        $wrong = $this->post('/admin/login', ['email' => 'admin@tutorhub.test', 'password' => 'wrong-pass'])->assertSessionHasErrors('email');
        $tutor = $this->post('/admin/login', ['email' => 'tutor1@example.com', 'password' => 'password'])->assertSessionHasErrors('email');

        $messages = collect([$unknown, $wrong, $tutor])->map(fn ($r) => session('errors')->first('email'));
        $this->assertCount(1, $messages->unique());

        $this->post('/admin/login', ['email' => 'admin@tutorhub.test', 'password' => 'Admin@1234'])->assertRedirect('/admin');
    }

    public function test_admin_login_is_rate_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', ['email' => 'admin@tutorhub.test', 'password' => 'wrong-pass']);
        }
        $this->post('/admin/login', ['email' => 'admin@tutorhub.test', 'password' => 'Admin@1234'])->assertSessionHasErrors('email');
        $this->assertStringContainsString('Too many attempts', session('errors')->first('email'));
    }

    public function test_admin_pages_render(): void
    {
        $match = Application::whereNotNull('sent_to_admin_at')->firstOrFail();

        $this->actingAs($this->admin());
        foreach (['/admin', '/admin/matches', '/admin/matches?status=pending_admin&search=Farhana', '/admin/matches/application/'.$match->id,
            '/admin/ai', '/admin/review', '/admin/subjects', '/admin/areas', '/admin/categories'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_match_page_shows_match_quality(): void
    {
        $match = Application::whereHas('tutor', fn ($q) => $q->where('name', 'Tanvir Ahmed'))->firstOrFail();

        $this->actingAs($this->admin())->get('/admin/matches/application/'.$match->id)->assertOk()->assertSee('Match quality');
    }

    public function test_full_match_flow_and_one_commission_per_match(): void
    {
        $match = Application::whereHas('tutor', fn ($q) => $q->where('name', 'Farhana Islam'))->firstOrFail();
        $base = '/admin/matches/application/'.$match->id;
        $this->actingAs($this->admin());

        $this->post($base.'/share-contact')->assertSessionHas('error');
        $this->post($base.'/mark-contacted')->assertSessionHas('success');
        $this->post($base.'/record-commission', ['amount' => 2500, 'payment_method' => 'bank_transfer'])->assertSessionHas('success');
        $this->post($base.'/record-commission', ['amount' => 2500, 'payment_method' => 'bkash'])->assertSessionHas('error');
        $this->post($base.'/share-contact')->assertSessionHas('success');

        $match->refresh();
        $this->assertNotNull($match->parent_contact_shared_at);
        $this->assertSame(1, Commission::where('match_type', 'application')->where('match_id', $match->id)->count());
        $this->assertSame('bank_transfer', Commission::where('match_id', $match->id)->value('payment_method'));
    }

    public function test_unknown_match_types_are_not_found(): void
    {
        $this->actingAs($this->admin())->get('/admin/matches/commission/1')->assertNotFound();
    }

    public function test_review_page_lists_flagged_profiles_and_verifies(): void
    {
        $rafiq = TutorProfile::whereHas('user', fn ($q) => $q->where('name', 'Rafiq Islam'))->firstOrFail();

        $this->actingAs($this->admin())->get('/admin/review')->assertOk()
            ->assertSee('Rafiq Islam')->assertSee('Same phone number as Rafsan Kabir');

        $this->post('/admin/tutors/'.$rafiq->id.'/verify')->assertRedirect();
        $this->assertTrue($rafiq->fresh()->is_verified);
    }

    public function test_only_admins_reach_admin_pages(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->actingAs($this->parentUser())->get('/admin')->assertForbidden();
        $this->actingAs($this->tutor('Nusrat Jahan'))->get('/admin/review')->assertForbidden();
    }
}
