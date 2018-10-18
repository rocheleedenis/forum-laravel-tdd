<?php

namespace Tests\Feature;

use Tests\TestCase;

class ParticipateInThreadsTest extends TestCase
{
    /**
     * @test
     */
    public function uthenticatedUsersMayNotAddReplies()
    {
        $thread = create('App\Thread');
        $reply  = make('App\Reply');

        $this->withExceptionHandling()
            ->post($thread->path() . '/replies', $reply->toArray())
            ->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function anAuthenticatedUserMayParticipateInForumThreads()
    {
        $thread = create('App\Thread');

        $this->signIn();

        $reply = make('App\Reply');

        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);
    }

    /**
     * @test
     */
    public function aReplyRequiresABody()
    {
        $thread = create('App\Thread');

        $this->signIn();

        $reply = make('App\Reply', ['body' => null]);

        $this->withExceptionHandling()
            ->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /**
     * @test
     */
    public function uthenticatedUsersCannotDeleteReplies()
    {
        $reply = create('App\Reply');

        $this->withExceptionHandling()
            ->delete("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function authorizedUsersCanDeleteReplies()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    /**
         * @test
         */
    public function authorizedUsersCannotUpdateReplies()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->patch("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function authorizedUsersCanUpdateReplies()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $updateReply = 'You been changed, fool.';
        $this->patch("/replies/{$reply->id}", ['body' => $updateReply]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updateReply]);
    }
}
