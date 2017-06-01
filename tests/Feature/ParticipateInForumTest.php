<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForum extends TestCase
{
	use DatabaseMigrations;

	public function setUp()
	{
		parent::setUp();
		$this->thread = create('App\Thread');
    	$this->reply = make('App\Reply');	//<-- 'make()' does not persist Reply in the DB, just creates in memory
	}

	/** @test */
	function unauthenticated_users_may_not_participate_in_forum_threads()
	{
		$this->withExceptionHandling()
			->post($this->thread->path().'/replies', [])
			->assertRedirect('/login');
	}

    /** @test */
    function authenticated_user_may_participate_in_thread()
    {
    	// make sure we're using an authenticated user
    	$this->signIn();

    	// post a reply to the server
    	$this->post($this->thread->path().'/replies', $this->reply->toArray());
    	
    	// visit the thread page and validate the new reply is there
    	$this->get($this->thread->path())
    		->assertSee($this->reply->body);
    }

    /** @test */
    function reply_requires_body()
    {
    	$this->withExceptionHandling()->signIn();
    	$badReply = make('App\Reply', ['body' => null]);
		$this->post($this->thread->path().'/replies', $badReply->toArray())
			->assertSessionHasErrors('body');

    }
}
