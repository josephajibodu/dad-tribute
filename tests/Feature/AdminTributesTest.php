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

    public function test_the_page_does_not_exist_when_no_admin_password_is_set(): void
    {
        config(['tribute.admin_password' => null]);

        $this->get(route('admin.tributes'))->assertNotFound();
    }

    public function test_the_page_asks_for_a_password(): void
    {
        $response = $this->get(route('admin.tributes'));

        $response->assertUnauthorized();
        $response->assertHeader('WWW-Authenticate', 'Basic realm="Tribute admin", charset="UTF-8"');
    }

    public function test_a_wrong_password_is_turned_away(): void
    {
        Tribute::factory()->create(['message' => 'A private message']);

        $response = $this->withBasicAuth('admin', 'wrong')->get(route('admin.tributes'));

        $response->assertUnauthorized();
        $response->assertDontSee('A private message');
    }

    public function test_the_right_password_shows_every_submitted_tribute(): void
    {
        Tribute::factory()->create(['name' => 'Waiting Visitor', 'message' => 'Rest well, sir.']);
        Tribute::factory()->approved()->create(['name' => 'Approved Visitor', 'message' => 'A great man.']);
        Tribute::factory()->candleOnly()->create(['name' => 'Quiet Visitor']);

        $response = $this->withBasicAuth('admin', 'correct-horse')->get(route('admin.tributes'));

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

    public function test_viewing_the_page_many_times_is_never_rate_limited(): void
    {
        for ($i = 0; $i < 30; $i++) {
            $this->flushHeaders()->get(route('admin.tributes'))->assertUnauthorized();
            $this->withBasicAuth('admin', 'correct-horse')->get(route('admin.tributes'))->assertOk();
        }
    }

    public function test_lighting_candles_does_not_use_up_the_admin_page_limit(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $this->post(route('tributes.store'), ['name' => 'Visitor '.$i]);
        }

        $this->post(route('tributes.store'), ['name' => 'One too many'])->assertTooManyRequests();

        $this->withBasicAuth('admin', 'correct-horse')->get(route('admin.tributes'))->assertOk();
    }

    public function test_too_many_wrong_passwords_make_the_visitor_wait(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->withBasicAuth('admin', 'guess-'.$i)->get(route('admin.tributes'))->assertUnauthorized();
        }

        $response = $this->withBasicAuth('admin', 'correct-horse')->get(route('admin.tributes'));

        $response->assertTooManyRequests();
        $response->assertHeader('Retry-After');
    }

    public function test_visitor_messages_are_escaped(): void
    {
        Tribute::factory()->create(['message' => '<script>alert(1)</script>']);

        $response = $this->withBasicAuth('admin', 'correct-horse')->get(route('admin.tributes'));

        $response->assertDontSee('<script>alert(1)</script>', false);
        $response->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
    }

    public function test_the_tribute_page_does_not_link_to_the_admin_page(): void
    {
        $this->get(route('tribute'))->assertDontSee(route('admin.tributes'));
    }
}
