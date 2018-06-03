<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function testGuesCanFavoriteAnything()
    {
        $this->withExceptionHandling()
            ->post('replies/1/favorites')
            ->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function testAnAuthenticatedUserCanFavoriteAnyReply()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $this->post('replies/' . $reply->id . '/favorites');

        $this->assertCount(1, $reply->favorites);
    }

    /**
     * @test
     */
    public function testAnAuthenticatedUserMayOnlyFavoriteAReplyOnce()
    {
        $this->signIn();

        $reply = create('App\Reply');

        try {
            $this->post('replies/' . $reply->id . '/favorites');
            $this->post('replies/' . $reply->id . '/favorites');
        } catch (\Exception $e) {
            $this->fail('Did not expect to insert the same record set twice.');
        }
        $this->assertCount(1, $reply->favorites);
    }

    /**
     * @test
     */
    public function testAnAuthenticatedUserCanUnfavoriteAnyReply()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $reply->favorite();

        $this->delete('replies/' . $reply->id . '/favorites');

        $this->assertCount(0, $reply->favorites);
    }
}
