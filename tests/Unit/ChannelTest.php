<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChannelTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAChannelConsistsOfThreads()
    {
        $channel = create('App\Channel');
        $thread  = create('App\Thread', ['channel_id' => $channel->id]);

        $this->assertTrue($channel->threads->contains($thread));
    }
}
