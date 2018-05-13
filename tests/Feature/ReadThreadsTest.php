<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->thread = create('App\Thread');
    }

    public function testAUserCanViewAllThreads()
    {
        $response = $this->get('/threads')
            ->assertSee($this->thread->title);
    }

    public function testAUserCanReadASingleThread()
    {
        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    public function testAUserCanReadRepliesThatAreAssocuatedWithAThread()
    {
        $reply = create('App\Reply', ['thread_id' => $this->thread->id]);

        $this->get($this->thread->path())
            ->assertSee($reply->body);
    }
}
