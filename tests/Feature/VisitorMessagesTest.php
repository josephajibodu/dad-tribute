<?php

namespace Tests\Feature;

use App\Models\Tribute;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitorMessagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_approved_messages_show_just_below_the_candle_form(): void
    {
        config(['tribute.family_tributes' => [['name' => 'Joseph Ajibodu', 'relation' => 'First son', 'text' => 'Rest well, Dad.']]]);
        Tribute::factory()->approved()->create(['name' => 'Ade', 'message' => 'He was a kind man.']);

        $response = $this->get(route('tribute'));

        $response->assertSeeInOrder(['Rest well, Dad.', 'id="service"', 'data-candle-form', '</form>', 'id="messages"', 'Words left', 'He was a kind man.', 'site-footer'], false);
        $response->assertSee('A message from those who lit a candle in his memory.');
    }

    public function test_waiting_and_candle_only_tributes_stay_off_the_page(): void
    {
        Tribute::factory()->create(['name' => 'Waiting Visitor', 'message' => 'Not yet seen.']);
        Tribute::factory()->approved()->candleOnly()->create(['name' => 'Quiet Visitor']);

        $response = $this->get(route('tribute'));

        $response->assertDontSee('Not yet seen.');
        $response->assertDontSee('Quiet Visitor');
        $response->assertDontSee('id="messages"', false);
        $response->assertDontSee('Read what others have written');
    }

    public function test_the_candle_section_points_to_the_messages_once_there_are_some(): void
    {
        Tribute::factory()->approved()->count(2)->create();

        $response = $this->get(route('tribute'));

        $response->assertSee('2 messages from those who lit a candle in his memory.');
        $response->assertSee('<a href="#messages" class="candle-read">Read what others have written</a>', false);
    }

    public function test_every_approved_message_is_on_the_page_newest_approval_first(): void
    {
        Tribute::factory()->count(70)->sequence(fn ($sequence) => [
            'message' => 'Message number '.$sequence->index.'.',
            'approved_at' => now()->subMinutes(100 - $sequence->index),
        ])->create();

        $response = $this->get(route('tribute'));

        $response->assertSeeInOrder(['data-message-track', 'Message number 69.', 'Message number 68.', 'Message number 0.'], false);
        $this->assertSame(70, substr_count($response->getContent(), 'data-message>'));
        $response->assertDontSee('Read more messages');
    }

    public function test_visitor_messages_are_escaped_on_the_tribute_page(): void
    {
        Tribute::factory()->approved()->create(['message' => '<script>alert(1)</script>']);

        $response = $this->get(route('tribute'));

        $response->assertDontSee('<script>alert(1)</script>', false);
        $response->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
    }
}
