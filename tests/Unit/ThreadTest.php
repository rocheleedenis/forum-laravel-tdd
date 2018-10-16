<?php

namespace Tests\Unit;

use Tests\TestCase;

class ThreadTest extends TestCase
{
    /**
     * @test
     */
    public function theThreadCanMakeAStringPath()
    {
        $thread = create('App\Thread');

        $this->assertEquals(
            "/threads/{$thread->channel->slug}/{$thread->id}",
            $thread->path()
        );
    }

    /**
     * @test
     */
    public function aThreadHasReplies()
    {
        $thread = create('App\Thread');

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $thread->replies);
    }

    /**
     * @test
     */
    public function aThreadHasACreator()
    {
        $thread = create('App\Thread');

        $this->assertInstanceOf('App\User', $thread->creator);
    }

    /**
     * @test
     */
    public function aThreadCanAddAReply()
    {
        $thread = create('App\Thread');

        $thread->addReply([
            'body'    => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $thread->replies);
    }

    /**
     * @test
     */
    public function aThreadBelongsToAChannel()
    {
        $thread = create('App\Thread');

        $this->assertInstanceOf('App\Channel', $thread->channel);
    }

    /**
     * @test
     */
    public function a_thred_can_be_subscribed_to()
    {
        $thread = create('App\Thread');

        $thread->subscribe($userId = 1);

        $this->assertEquals(
            1,
            $thread->subscriptions()->where('user_id', $userId)->count()
        );
    }

    /**
     * @test
     */
    public function a_thred_can_be_unsubscribed_from()
    {
        $thread = create('App\Thread');

        $thread->subscribe($userId = 1);

        $thread->unsubscribe($userId = 1);

        $this->assertCount(0, $thread->subscriptions);
    }
}
