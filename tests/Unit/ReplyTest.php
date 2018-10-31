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
}
