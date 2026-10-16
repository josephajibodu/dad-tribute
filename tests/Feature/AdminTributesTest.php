<?php

namespace Tests\Feature;

use App\Models\Tribute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTributesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['tribute.admin_password' => 'correct-horse']);
    }

    public function test_the_admin_pages_do_not_exist_when_no_admin_password_is_set(): void
    {
        config(['tribute.admin_password' => null]);

        $this->get(route('admin.tributes'))->assertNotFound();
        $this->get(route('admin.login'))->assertNotFound();
        $this->post(route('admin.login.store'), ['password' => ''])->assertNotFound();
    }

    public function test_visiting_without_signing_in_shows_the_sign_in_page(): void
    {
        Tribute::factory()->create(['message' => 'A private message']);

        $response = $this->get(route('admin.tributes'));

        $response->assertRedirect(route('admin.login'));
        $this->followRedirects($response)->assertOk()->assertSee('Sign in')->assertDontSee('A private message');
    }

    public function test_visiting_many_times_without_signing_in_never_locks_the_page(): void
    {
        for ($i = 0; $i < 30; $i++) {
            $this->get(route('admin.tributes'))->assertRedirect(route('admin.login'));
            $this->get(route('admin.login'))->assertOk();
        }

        $this->post(route('admin.login.store'), ['password' => 'correct-horse'])->assertRedirect(route('admin.tributes'));
    }

    public function test_a_wrong_password_is_turned_away(): void
    {
        $response = $this->from(route('admin.login'))->post(route('admin.login.store'), ['password' => 'wrong']);

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHasErrors(['password' => 'That password is not right.']);
        $this->assertNull(session('admin_signed_in'));
    }

    public function test_too_many_wrong_passwords_make_the_visitor_wait(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->post(route('admin.login.store'), ['password' => 'guess-'.$i]);
        }

        $response = $this->post(route('admin.login.store'), ['password' => 'correct-horse']);

        $response->assertSessionHasErrors('password');
        $this->assertStringContainsString('Too many wrong passwords', session('errors')->first('password'));
        $this->assertNull(session('admin_signed_in'));
    }

    public function test_signing_in_shows_every_submitted_tribute(): void
    {
        Tribute::factory()->create(['name' => 'Waiting Visitor', 'message' => 'Rest well, sir.']);
        Tribute::factory()->approved()->create(['name' => 'Approved Visitor', 'message' => 'A great man.']);
        Tribute::factory()->candleOnly()->create(['name' => 'Quiet Visitor']);

        $this->post(route('admin.login.store'), ['password' => 'correct-horse'])->assertRedirect(route('admin.tributes'));

        $response = $this->get(route('admin.tributes'));

        $response->assertOk();
        $response->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $response->assertSee('Waiting Visitor');
        $response->assertSee('Rest well, sir.');
        $response->assertSee('Approved Visitor');
        $response->assertSee('Quiet Visitor');
        $response->assertSee('Lit a candle, no message.');
        $response->assertSee('data-status="pending"', false);
        $response->assertSee('data-status="approved"', false);
        $response->assertSee('data-status="candle"', false);
    }

    public function test_signing_in_returns_to_the_page_that_was_asked_for(): void
    {
        $this->get(route('admin.tributes'));

        $this->post(route('admin.login.store'), ['password' => 'correct-horse'])->assertRedirect(route('admin.tributes'));
    }

    public function test_signing_out_locks_the_page_again(): void
    {
        $this->withSession(['admin_signed_in' => true])->get(route('admin.tributes'))->assertOk();

        $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));

        $this->get(route('admin.tributes'))->assertRedirect(route('admin.login'));
    }

    public function test_lighting_candles_does_not_use_up_the_admin_sign_in_limit(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $this->post(route('tributes.store'), ['name' => 'Visitor '.$i]);
        }

        $this->post(route('tributes.store'), ['name' => 'One too many'])->assertTooManyRequests();

        $this->post(route('admin.login.store'), ['password' => 'correct-horse'])->assertRedirect(route('admin.tributes'));
    }

    public function test_visitor_messages_are_escaped(): void
    {
        Tribute::factory()->create(['message' => '<script>alert(1)</script>']);

        $response = $this->withSession(['admin_signed_in' => true])->get(route('admin.tributes'));

        $response->assertDontSee('<script>alert(1)</script>', false);
        $response->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
    }

    public function test_the_tribute_page_does_not_link_to_the_admin_pages(): void
    {
        $response = $this->get(route('tribute'));

        $response->assertDontSee(route('admin.tributes'));
        $response->assertDontSee(route('admin.login'));
    }
}
