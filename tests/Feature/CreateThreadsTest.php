<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

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

        $thread = create('App\Thread');
        $this->post('/threads', $thread->toArray());

        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
