<?php

namespace Tests\Feature;

use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    /**
    * @test
    **/
    public function guest_may_not_create_threads()
    {
        $this->withExceptionHandling();

        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post(route('threads'))
            ->assertRedirect('/login');
    }

    /**
    * @test
    **/
    public function new_users_must_first_confirm_their_email_address_before_creating_threads()
    {
        $user = factory('App\User')->states('unconfirmed')->create();

        $this->signIn($user);

        $thread = make('App\Thread');

        $this->post(route('threads'), $thread->toArray())
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'You must first confirm your e-mail address.');
    }

    /**
    * @test
    **/
    public function an_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $thread = make('App\Thread');

        $response = $this->post(route('threads'), $thread->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /**
    * @test
    **/
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /**
    * @test
    **/
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /**
    * @test
    **/
    public function a_thread_requires_a_valid_channel()
    {
        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /**
    * @test
    **/
    public function unautorized_user_may_not_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create('App\Thread');

        $this->delete($thread->path())
            ->assertRedirect('/login');

        $this->signIn();

        $this->delete($thread->path())
            ->assertStatus(403);
    }

    /**
    * @test
    **/
    public function autorized_user_can_delete_threads()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply  = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id'=> $thread->id]);
        $this->assertDatabaseMissing('replies', ['id'=> $reply->id]);

        $this->assertDatabaseMissing('activities', [
            'subject_id'   => $thread->id,
            'subject_type' => get_class($thread)
        ]);
        $this->assertDatabaseMissing('activities', [
            'subject_id'   => $reply->id,
            'subject_type' => get_class($reply)
        ]);
    }

    public function publishThread($overredes = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overredes);

        return $this->post(route('threads'), $thread->toArray());
    }
}
