<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase{

    use DatabaseMigrations;

    /** @test */
    function unauthenticated_users_cannot_favorite_anything()
    {

        $this->withExceptionHandling()
            ->post('/replies/1/favorites')
            ->assertRedirect('/login');
    }

    /** @test */
    function authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');
        $this->post('/replies/' . $reply->id . '/favorites');
        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    function authenticated_user_can_favorite_reply_only_once()
    {
        $this->signIn();

        $reply = create('App\Reply');

        try {
            $this->post('/replies/' . $reply->id . '/favorites');
            $this->post('/replies/' . $reply->id . '/favorites');
        } catch(\Exception $e) {
            $this->fail('Duplicate record in "favorites" table.');
        }


        $this->assertCount(1, $reply->favorites);
    }
}