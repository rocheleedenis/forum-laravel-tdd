<?php

namespace Tests\Feature;

use Tests\TestCase;

class LockThreadsTest extends TestCase
{
    /**
     * @test
     */
    public function an_administrator_can_lock_any_thread()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $thread->lock();

        $this->post($thread->path() . '/replies', [
            'body'    => 'Foobar',
            'user_id' => create('App\User')->id
        ])->assertStatus(422);
    }
}
