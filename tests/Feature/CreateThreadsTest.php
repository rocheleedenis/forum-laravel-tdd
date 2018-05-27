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

    public function testGuestMayNotCreateThreads()
    {
        $this->withExceptionHandling()
            ->get('/threads/create')
            ->assertRedirect('/login');

        $this->withExceptionHandling()
            ->post('/threads')
            ->assertRedirect('/login');
    }

    public function testAnAuthenticateUserCanCreateNewForumThreads()
    {
        $this->signIn();

        $thread = make('App\Thread');

        $response = $this->post('/threads', $thread->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    public function testAThreadRequiresATitle()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    public function testAThreadRequiresABody()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    public function testAThreadRequiresAValidChannel()
    {
        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');
        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    public function publishThread($overredes = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overredes);

        return $this->post('/threads', $thread->toArray());
    }
}
