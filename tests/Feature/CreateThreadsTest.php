<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function testGuestMayNotCreateThreads()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->withoutExceptionHandling();

        $thread = factory('App\Thread')->make();
        $this->post('/threads/store', $thread->toArray());

        $this->assertRedirect('/login');
    }

    public function testAnAuthenticateUserCanCreateNewForumThreads()
    {
        $this->actingAs(factory('App\User')->create());

        $thread = factory('App\Thread')->make();
        $this->post('/threads/store', $thread->toArray());

        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
