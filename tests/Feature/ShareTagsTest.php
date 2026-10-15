<?php

namespace Tests\Feature;

use Tests\TestCase;

class ShareTagsTest extends TestCase
{
    public function test_link_previews_use_the_share_card_and_summary(): void
    {
        $response = $this->get(route('tribute'));

        $response->assertOk();
        $response->assertSee('<meta property="og:image" content="'.asset('images/share-card.jpg').'">', false);
        $response->assertSee('<meta property="og:image:width" content="1200">', false);
        $response->assertSee('<meta property="og:image:height" content="630">', false);
        $response->assertSee('<meta name="twitter:card" content="summary_large_image">', false);
        $response->assertSee('<meta property="og:description" content="'.e(config('tribute.summary')).'">', false);
        $response->assertSee('<meta property="og:title" content="In Loving Memory of Samson Toyin Ajibodu (1969 – 2026)">', false);
    }

    public function test_link_previews_fall_back_to_the_portrait_when_the_share_card_is_missing(): void
    {
        config(['tribute.share_image' => 'images/missing-share-card.jpg']);

        $response = $this->get(route('tribute'));

        $response->assertOk();
        $response->assertSee('<meta property="og:image" content="'.asset('images/portrait.jpg').'">', false);
        $response->assertSee('<meta property="og:image:width" content="660">', false);
        $response->assertSee('<meta property="og:image:height" content="867">', false);
    }
}
