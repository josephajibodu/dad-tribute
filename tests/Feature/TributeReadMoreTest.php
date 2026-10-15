<?php

namespace Tests\Feature;

use App\Models\Tribute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TributeReadMoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_family_tribute_with_an_excerpt_shows_the_excerpt_and_offers_the_full_text(): void
    {
        config(['tribute.family_tributes' => [[
            'name' => 'Joseph Ajibodu',
            'relation' => 'First son',
            'title' => 'A Tribute to My Dad',
            'excerpt' => 'Everything you invested in me lives on.',
            'text' => "Thank you for carrying me to school.\n\n**Rest well, Dad.**",
        ]]]);

        $response = $this->get(route('tribute'));

        $response->assertOk();
        $response->assertSee('<p class="tribute__text">Everything you invested in me lives on.</p>', false);
        $response->assertSee('Read the full tribute');
        $response->assertSee('<h3 class="reader__title">A Tribute to My Dad</h3>', false);
        $response->assertSee('<p>Thank you for carrying me to school.</p>', false);
        $response->assertSee('<strong>Rest well, Dad.</strong>', false);
    }

    public function test_a_short_family_tribute_shows_in_full_without_a_read_more_link(): void
    {
        config(['tribute.family_tributes' => [[
            'name' => 'A friend',
            'relation' => 'Friend',
            'text' => 'He was kind to everyone.',
        ]]]);

        $response = $this->get(route('tribute'));

        $response->assertSee('<p class="tribute__text">He was kind to everyone.</p>', false);
        $response->assertDontSee('data-reader-open', false);
    }

    public function test_the_full_text_keeps_line_breaks_and_strips_html(): void
    {
        config(['tribute.family_tributes' => [[
            'name' => 'Larry Ajibodu',
            'relation' => 'Son',
            'excerpt' => 'Gone too soon.',
            'text' => "You were a good father.\nNot just to us your children, but to many.<script>alert(1)</script>",
        ]]]);

        $response = $this->get(route('tribute'));

        $response->assertSee("You were a good father.<br>\nNot just to us your children, but to many.", false);
        $response->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_a_long_visitor_message_is_shortened_with_a_read_more_link(): void
    {
        config(['tribute.family_tributes' => []]);
        $message = trim(str_repeat('He taught us well. ', 30));
        Tribute::create(['name' => 'Ade', 'message' => $message, 'approved_at' => now(), 'ip_hash' => 'x']);

        $response = $this->get(route('tribute'));

        $response->assertSee('Read more');
        $response->assertSee('<p class="reader__text reader__text--plain">'.$message.'</p>', false);
        $response->assertDontSee('<p class="tribute__text">'.$message.'</p>', false);
    }

    public function test_a_short_visitor_message_shows_in_full(): void
    {
        config(['tribute.family_tributes' => []]);
        Tribute::create(['name' => 'Ade', 'message' => 'Rest well, sir.', 'approved_at' => now(), 'ip_hash' => 'x']);

        $response = $this->get(route('tribute'));

        $response->assertSee('<p class="tribute__text">Rest well, sir.</p>', false);
        $response->assertDontSee('data-reader-open', false);
    }
}
