<?php

namespace Tests\Feature;

use Tests\TestCase;

class ShareTagsTest extends TestCase
{
    public function test_link_previews_use_the_share_card_and_summary(): void
    {
        $response = $this->get(route('tribute'));

        $response->assertOk();
        $response->assertSee('<meta property="og:image" content="https://assets-3bqntuhgsbwwdx.flitops.xyz/share-card.jpg">', false);
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
        $response->assertSee('<meta property="og:image" content="https://assets-3bqntuhgsbwwdx.flitops.xyz/portrait.jpg">', false);
        $response->assertSee('<meta property="og:image:width" content="660">', false);
        $response->assertSee('<meta property="og:image:height" content="867">', false);
    }

    public function test_photos_are_served_from_the_cloudflare_bucket_in_the_same_folders(): void
    {
        config(['tribute.images_url' => 'https://assets.example.com/']);

        $response = $this->get(route('tribute'));

        $response->assertOk();
        $response->assertSee('src="https://assets.example.com/portrait.jpg"', false);
        $response->assertSee('https://assets.example.com/moments/01.jpg', false);
        $response->assertDontSee(asset('images/moments/01.jpg'), false);
    }

    public function test_photos_are_served_from_this_site_when_no_images_url_is_set(): void
    {
        config(['tribute.images_url' => null]);

        $response = $this->get(route('tribute'));

        $response->assertOk();
        $response->assertSee('src="'.asset('images/portrait.jpg').'"', false);
        $response->assertSee('<meta property="og:image" content="'.asset('images/share-card.jpg').'">', false);
    }
}
