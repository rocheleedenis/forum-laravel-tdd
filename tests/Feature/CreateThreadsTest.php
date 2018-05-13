<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function testGuestMayNotCreateThreads()
    {
        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray())
            ->assertRedirect('/login');
    }

    public function testAnAuthenticateUserCanCreateNewForumThreads()
    {
        $this->signIn();

        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray());

        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
