<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Support\Facades\Notification;

class ThreadTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->thread = create('App\Thread');
    }

    /**
     * @test
     */
    public function theThreadCanMakeAStringPath()
    {
        $this->assertEquals(
            "/threads/{$this->thread->channel->slug}/{$this->thread->id}",
            $this->thread->path()
        );
    }

    /**
     * @test
     */
    public function aThreadHasReplies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /**
     * @test
     */
    public function aThreadHasACreator()
    {
        $this->assertInstanceOf('App\User', $this->thread->creator);
    }

    /**
     * @test
     */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body'    => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /**
    * @test
    */
    public function a_thread_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
        Notification::fake();

        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
                'body'    => 'Foobar',
                'user_id' => 999
            ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /**
     * @test
     */
    public function aThreadBelongsToAChannel()
    {
        $this->assertInstanceOf('App\Channel', $this->thread->channel);
    }

    /**
     * @test
     */
    public function a_thred_can_be_subscribed_to()
    {
        $this->thread->subscribe($userId = 1);

        $this->assertEquals(
            1,
            $this->thread->subscriptions()->where('user_id', $userId)->count()
        );
    }

    /**
     * @test
     */
    public function a_thred_can_be_unsubscribed_from()
    {
        $this->thread->subscribe($userId = 1);

        $this->thread->unsubscribe($userId = 1);

        $this->assertCount(0, $this->thread->subscriptions);
    }

    /**
     * @test
     */
    public function it_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        $this->signIn();

        $this->assertFalse($this->thread->isSubscribedTo);

        $this->thread->subscribe();

        $this->assertTrue($this->thread->isSubscribedTo);
    }

    /**
     * @test
     */
    public function a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();

        $thread = create('App\Thread');

        tap(auth()->user(), function ($user) use ($thread) {
            $this->assertTrue($thread->hasUpdatedFor($user));

            // simulate that the user visited the thread.
            $user->read($thread);

            $this->assertFalse($thread->hasUpdatedFor($user));
        });
    }

    /**
     * @test
     */
    public function a_thread_records_each_visit()
    {
        $thread = make('App\Thread', ['id' => 1]);

        $thread->visits()->reset();

        $this->assertSame(0, $thread->visits()->count());

        $thread->visits()->record();

        $this->assertEquals(1, $thread->visits()->count());

        $thread->visits()->record();

        $this->assertEquals(2, $thread->visits()->count());
    }
}
