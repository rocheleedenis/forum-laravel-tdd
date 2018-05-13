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

        $this->thread = create('App\Thread');
        $this->reply  = make('App\Reply');
    }

    public function testUthenticatedUsersMayNotAddReplies()
    {
        $this->post($this->thread->path() . '/replies', $this->reply->toArray())
            ->assertRedirect('/login');
    }

    public function testAnAuthenticatedUserMayParticipateInForumThreads()
    {
        $this->signIn();

        $this->post($this->thread->path() . '/replies', $this->reply->toArray());

        $this->get($this->thread->path())
            ->assertSee($this->reply->body);
    }
}
