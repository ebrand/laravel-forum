<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
	use DatabaseMigrations;

	public function setUp()
	{
		parent::setUp();
		$this->thread = make('App\Thread');
	}

	/** @test */
	function unauthenticated_users_may_not_create_thread()
	{
		$this->withExceptionHandling();
		
		$this->get('/threads/create')
			->assertRedirect('/login');

		$this->post('/threads')
			->assertRedirect('/login');
	}

	/** @test */
	function authenticated_user_may_create_thread()
	{
		$this->signIn();

		$response = $this->post('/threads', $this->thread->toArray());
		$redirectUrl = $response->headers->get('Location');

		$this->get($redirectUrl)
			->assertSee($this->thread->title)
			->assertSee($this->thread->body);
	}

	/** @test */
	function thread_requires_title()
	{	
		$this->publishThread(['title' => null])
			->assertSessionHasErrors('title');
	}

	/** @test */
	function thread_requires_body()
	{
		$this->publishThread(['body' => null])
			->assertSessionHasErrors('body');
	}

	/** @test */
	function thread_requires_channel()
	{
		factory('App\Channel', 2)->create();

		$this->publishThread(['channel_id' => null])
			->assertSessionHasErrors('channel_id');

		$this->publishThread(['channel_id' => 999])
			->assertSessionHasErrors('channel_id');
	}

	function publishThread($overrides = [])
	{
		$this->withExceptionHandling()->signIn();
		$thread = make('App\Thread', $overrides);
		return $this->post('/threads', $thread->toArray());
	}
}
















