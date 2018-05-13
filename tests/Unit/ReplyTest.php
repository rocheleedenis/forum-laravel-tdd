<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    public function testItHasAnOwner()
    {
        $reply = create('App\Reply');

        $this->assertInstanceOf('App\User', $reply->owner);
    }
}
