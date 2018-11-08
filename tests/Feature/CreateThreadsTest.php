<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Rules\Recaptcha;

class CreateThreadsTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        app()->singleton(Recaptcha::class, function () {
            return \Mockery::mock(Recaptcha::class, function ($m) {
                $m->shouldReceive('passes')->andReturn(true);
            });

            return $m;
        });
    }

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
        $response = $this->publishThread([
            'title' => 'Some title',
            'body'  => 'Some body.'
        ]);

        $this->get($response->headers->get('Location'))
            ->assertSee('Some title')
            ->assertSee('Some body.');
    }

    /**
    * @test
    **/
    public function a_thread_requires_a_unique_slug()
    {
        $this->signIn();

        $thread = create('App\Thread', ['title' => 'Foo Title']);

        $this->assertEquals($thread->fresh()->slug, 'foo-title');

        $thread = $this->postJson(
            route('threads'),
            $thread->toArray() + ['g-recaptcha-response' => 'test']
        )->json();

        $this->assertEquals("foo-title-{$thread['id']}", $thread['slug']);
    }

    /**
    * @test
    **/
    public function a_thread_with_a_title_ends_in_a_number_should_generate_the_proper_slug()
    {
        $this->signIn();

        $thread = create('App\Thread', ['title' => 'Some Title 24']);

        $thread = $this->postJson(
            route('threads'),
            $thread->toArray() + ['g-recaptcha-response' => 'test']
        )->json();

        $this->assertEquals("some-title-24-{$thread['id']}", $thread['slug']);
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
    public function a_thread_requires_recaptcha_verification()
    {
        unset(app()[Recaptcha::class]);

        $this->publishThread(['g-recaptcha-response' => 'test'])
            ->assertSessionHasErrors('g-recaptcha-response');
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

        return $this->post(
            route('threads'),
            $thread->toArray() + ['g-recaptcha-response' => 'test']
        );
    }
}
