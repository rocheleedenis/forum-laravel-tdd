<?php

namespace Tests\Unit;

use Tests\TestCase;

class ReplyTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_an_owner()
    {
        $reply = create('App\Reply');

        $this->assertInstanceOf('App\User', $reply->owner);
    }

    /**
     * @test
     */
    public function it_knows_if_was_just_published()
    {
        $reply = create('App\Reply');

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = \Carbon\Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }

    /**
     * @test
     */
    public function it_can_detect_all_mentioned_users_in_the_body()
    {
        $reply = new \App\Reply([
            'body' => '@JaneDoe wants to talk to @JonDoe'
        ]);

        $this->assertEquals(['JaneDoe', 'JonDoe'], $reply->mentionedUsers());
    }

    /**
     * @test
     */
    public function it_wraps_mentioned_usernames_in_the_body_within_anchor_tags()
    {
        $reply = new \App\Reply([
            'body' => 'Hello @Jane-Doe.'
        ]);

        $this->assertEquals(
            'Hello <a href="/profiles/Jane-Doe">@Jane-Doe</a>.',
            $reply->body
        );
    }
}
