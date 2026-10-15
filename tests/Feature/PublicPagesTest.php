<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Level;
use App\Models\Subject;
use App\Models\TuitionOffer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_public_pages_render(): void
    {
        $offer = TuitionOffer::where('status', 'open')->firstOrFail();
        $tutor = $this->tutor('Nusrat Jahan');

        foreach ([
            '/', '/tutors', '/tutors?subject=Physics&class=SSC&sort=experience', '/tuition-offers', '/tuition-offers?sort=salary_high&type=home',
            '/tuition-offers/'.$offer->id, '/tutors/'.$tutor->tutor_code, '/categories',
            '/subject/'.Subject::first()->slug, '/level/'.Level::first()->slug, '/type/home', '/type/online', '/location/dhaka', '/location/dhaka/mirpur',
            '/about', '/contact', '/login', '/register',
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_tutor_directory_filters_by_json_columns(): void
    {
        $mirpur = Area::where('name', 'Mirpur')->value('id');

        $this->get('/tutors?subject=Economics')->assertOk()->assertSee('Imran Hossain')->assertDontSee('Farhana Islam');
        $this->get('/tutors?area='.$mirpur.'&gender=female')->assertOk()->assertSee('Nusrat Jahan')->assertDontSee('Tanvir Ahmed');
    }

    public function test_tutor_profile_works_by_code_and_by_old_numeric_link(): void
    {
        $tutor = $this->tutor('Tanvir Ahmed');

        $this->get('/tutors/'.$tutor->tutor_code)->assertOk()->assertSee('Tanvir Ahmed')->assertSee('Dhanmondi');
        $this->get('/tutors/'.$tutor->id)->assertOk();
        $this->get('/tutors/T-999999')->assertNotFound();
    }

    public function test_profiles_show_no_made_up_reviews(): void
    {
        $this->get('/tutors/'.$this->tutor('Tanvir Ahmed')->tutor_code)->assertDontSee('12 Reviews')->assertDontSee('15+');
        $this->get('/')->assertOk()->assertDontSee('45 reviews')->assertDontSee('#1 Tutor')->assertDontSee('What People Say')
            ->assertSee('How it works');
    }

    public function test_public_offer_page_hides_the_parent_and_the_address(): void
    {
        $offer = TuitionOffer::where('title', 'Mathematics and Physics tutor for Class 10')->firstOrFail();

        $this->get('/tuition-offers/'.$offer->id)->assertOk()
            ->assertDontSee($offer->user->name)
            ->assertDontSee($offer->location)
            ->assertSee('Mirpur');
    }

    public function test_browse_pages_list_the_right_tutors(): void
    {
        $this->get('/location/dhaka/uttara')->assertOk()->assertSee('Rahim Uddin')->assertDontSee('Nusrat Jahan');
        $this->get('/level/'.Level::where('name', 'Class 9-10 (SSC)')->value('slug'))->assertOk()->assertSee('Nusrat Jahan')->assertDontSee('Sumaiya Akter');
        $this->get('/type/online')->assertOk()->assertSee('Tasnim Rahman')->assertDontSee('Rahim Uddin');
    }

    public function test_filled_offers_are_not_public(): void
    {
        $this->get('/tuition-offers/'.TuitionOffer::where('status', 'filled')->value('id'))->assertNotFound();
    }
}
