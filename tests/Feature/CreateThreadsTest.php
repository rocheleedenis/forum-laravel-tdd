<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    /**
    * @test
    **/
    public function testGuestMayNotCreateThreads()
    {
        $this->withExceptionHandling()
            ->get('/threads/create')
            ->assertRedirect('/login');

        $this->withExceptionHandling()
            ->post('/threads')
            ->assertRedirect('/login');
    }

    /**
    * @test
    **/
    public function testAnAuthenticateUserCanCreateNewForumThreads()
    {
        $this->signIn();

        $thread = make('App\Thread');

        $response = $this->post('/threads', $thread->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /**
    * @test
    **/
    public function testAThreadRequiresATitle()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /**
    * @test
    **/
    public function testAThreadRequiresABody()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /**
    * @test
    **/
    public function testAThreadRequiresAValidChannel()
    {
        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');
        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /**
    * @test
    **/
    public function testGuestCannotDeleteThreads()
    {
        $this->withExceptionHandling();

        $thread = create('App\Thread');

        $response = $this->delete($thread->path());

        $response->assertRedirect('/login');
    }

    /**
    * @test
    **/
    public function testTheThreadCanBeDeleted()
    {
        $this->signIn();

        $thread = create('App\Thread');
        $reply  = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);
        $this->assertDatabaseMissing('threads', ['id'=> $thread->id]);
        $this->assertDatabaseMissing('replies', ['id'=> $reply->id]);
    }

    public function publishThread($overredes = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overredes);

        return $this->post('/threads', $thread->toArray());
    }
}
