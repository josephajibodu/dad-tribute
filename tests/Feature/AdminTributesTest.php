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

    public function test_approving_a_message_shows_it_on_the_tribute_page(): void
    {
        $tribute = Tribute::factory()->create(['name' => 'Waiting Visitor', 'message' => 'Rest well, sir.']);

        $this->get(route('tribute'))->assertDontSee('Rest well, sir.');

        $response = $this->withSession(['admin_signed_in' => true])
            ->from(route('admin.tributes', ['show' => 'pending']))
            ->post(route('admin.tributes.approve', $tribute));

        $response->assertRedirect(route('admin.tributes', ['show' => 'pending']));
        $response->assertSessionHas('status', "Waiting Visitor's message now shows on the tribute page.");
        $this->assertNotNull($tribute->fresh()->approved_at);
        $this->get(route('tribute'))->assertSee('Rest well, sir.');
    }

    public function test_approving_from_the_page_returns_the_redrawn_tribute_and_new_totals(): void
    {
        $tribute = Tribute::factory()->create(['message' => 'Rest well, sir.']);
        Tribute::factory()->create(['message' => 'Still waiting.']);
        Tribute::factory()->candleOnly()->create();

        $response = $this->withSession(['admin_signed_in' => true])
            ->postJson(route('admin.tributes.approve', $tribute));

        $response->assertOk();
        $response->assertJson(['counts' => ['candles' => 3, 'messages' => 2, 'pending' => 1]]);
        $this->assertStringContainsString('data-status="approved"', $response->json('html'));
        $this->assertStringContainsString('Hide from page', $response->json('html'));
    }

    public function test_approving_twice_keeps_the_first_approval_time(): void
    {
        $tribute = Tribute::factory()->create(['approved_at' => now()->subDay()]);
        $approvedAt = $tribute->approved_at;

        $this->withSession(['admin_signed_in' => true])->post(route('admin.tributes.approve', $tribute));

        $this->assertTrue($tribute->fresh()->approved_at->equalTo($approvedAt));
    }

    public function test_hiding_a_message_takes_it_off_the_tribute_page(): void
    {
        $tribute = Tribute::factory()->approved()->create(['message' => 'A great man.']);

        $this->withSession(['admin_signed_in' => true])
            ->deleteJson(route('admin.tributes.unapprove', $tribute))
            ->assertOk()
            ->assertJson(['counts' => ['pending' => 1]]);

        $this->assertNull($tribute->fresh()->approved_at);
        $this->get(route('tribute'))->assertDontSee('A great man.');
    }

    public function test_removing_a_tribute_deletes_it(): void
    {
        $tribute = Tribute::factory()->approved()->create();

        $response = $this->withSession(['admin_signed_in' => true])->deleteJson(route('admin.tributes.destroy', $tribute));

        $response->assertOk();
        $response->assertJson(['html' => null, 'counts' => ['candles' => 0]]);
        $this->assertModelMissing($tribute);
    }

    public function test_the_list_offers_the_right_actions_for_each_tribute(): void
    {
        $waiting = Tribute::factory()->create();
        $shown = Tribute::factory()->approved()->create();
        $candle = Tribute::factory()->candleOnly()->create();

        $response = $this->withSession(['admin_signed_in' => true])->get(route('admin.tributes'));

        $response->assertSee('action="'.route('admin.tributes.approve', $waiting).'"', false);
        $response->assertSee('action="'.route('admin.tributes.unapprove', $shown).'"', false);
        $response->assertDontSee('action="'.route('admin.tributes.approve', $candle).'"', false);
        $response->assertSee('action="'.route('admin.tributes.destroy', $candle).'"', false);
    }

    public function test_tributes_cannot_be_changed_without_signing_in(): void
    {
        $tribute = Tribute::factory()->create();

        $this->post(route('admin.tributes.approve', $tribute))->assertRedirect(route('admin.login'));
        $this->delete(route('admin.tributes.unapprove', $tribute))->assertRedirect(route('admin.login'));
        $this->delete(route('admin.tributes.destroy', $tribute))->assertRedirect(route('admin.login'));

        $this->assertNull($tribute->fresh()->approved_at);
    }

    public function test_the_tribute_page_does_not_link_to_the_admin_pages(): void
    {
        $response = $this->get(route('tribute'));

        $response->assertDontSee(route('admin.tributes'));
        $response->assertDontSee(route('admin.login'));
    }
}
