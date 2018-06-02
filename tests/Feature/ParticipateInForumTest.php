<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $this->thread = create('App\Thread');
        $this->reply  = make('App\Reply');
    }

    /**
     * @test
     */
    public function testUthenticatedUsersMayNotAddReplies()
    {
        $this->withExceptionHandling()
            ->post($this->thread->path() . '/replies', $this->reply->toArray())
            ->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function testAnAuthenticatedUserMayParticipateInForumThreads()
    {
        $this->signIn();

        $this->post($this->thread->path() . '/replies', $this->reply->toArray());

        $this->get($this->thread->path())
            ->assertSee($this->reply->body);
    }

    /**
     * @test
     */
    public function testAReplyRequiresABody()
    {
        $this->withExceptionHandling()->signIn();

        $this->reply->body = null;

        $this->post($this->thread->path() . '/replies', $this->reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /**
     * @test
     */
    public function testUthenticatedUsersCannotDeleteReplies()
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
    public function testAuthorizedUsersCanDeleteReplies()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    /**
         * @test
         */
    public function testAuthorizedUsersCannotUpdateReplies()
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
    public function testAuthorizedUsersCanUpdateReplies()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $updateReply = 'You been changed, fool.';
        $this->patch("/replies/{$reply->id}", ['body' => $updateReply]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updateReply]);
    }
}
