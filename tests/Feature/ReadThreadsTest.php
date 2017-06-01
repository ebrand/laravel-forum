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
		$this->reply  = create('App\Reply', ['thread_id' => $this->thread->id]);
	}

	/** @test */
	public function user_can_browse_threads()
	{
		$this->get('/threads')
			->assertSee($this->thread->title);
	}

	/** @test */
	public function user_can_read_a_single_thread()
	{
		$this->get($this->thread->path())
			->assertSee($this->thread->title);
	}
	
	/** @test */
	public function user_can_read_replies_associated_with_thread()
	{
		$this->get($this->thread->path())
			->assertSee($this->reply->body);
	}

	/** @test */
	function user_can_filter_threads_by_channel()
	{
		$channel = create('App\Channel');
		$threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
		$threadNotInChannel = create('App\Thread');

		$this->get('/threads/'.$channel->slug)
			->assertSee($threadInChannel->title)
			->assertDontSee($threadNotInChannel->title);
	}

	/** @test */
	function user_can_filter_threads_by_username()
	{
		$username = '123456789'; // <-- make this a Guid to ensure uniqueness?

		$this->signIn(create('App\User', ['name' => $username]));

		$threadByUser = create('App\Thread', ['user_id' => auth()->id()]);
		$threadNotByUser = create('App\Thread');

		$this->get('/threads?by='.$username)
			->assertSee($threadByUser->title)
			->assertDontSee($threadNotByUser->title);
	}

	/** @test */
	function user_can_filter_threads_by_popularity()
	{
		$threadWithZeroReplies = create('App\Thread');

		$threadWithTwoReplies = create('App\Thread');
		create('App\Reply', ['thread_id' => $threadWithTwoReplies->id], 2);
		
		$threadWithThreeReplies = create('App\Thread');
		create('App\Reply', ['thread_id' => $threadWithThreeReplies->id], 3);

		//dd($threadWithThreeReplies);

		$response = $this->getJson('/threads?popular=1')->json();
		
		$this->assertEquals([3, 2, 1, 0], array_column($response, 'replies_count'));
	}
}















