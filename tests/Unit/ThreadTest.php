<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;
    protected $thread;

	public function setUp()
	{
		parent::setUp();
		$this->thread = create('App\Thread');
	}

    /** @test */
    function thread_has_replies()
    {
    	$this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /** @test */
    function thread_has_owner()
    {
    	$this->assertInstanceOf('App\User', $this->thread->owner);
    }

    /** @test */
    function thread_can_add_reply()
    {
    	$this->thread->addReply([
    		'body' => 'foobar',
    		'user_id' => 1
    	]);
    	$this->assertCount(1, $this->thread->replies);
    }
    /** @test */
    function thread_belongs_to_channel()
    {
    	$thread = create('App\Thread');
    	$this->assertInstanceOf('App\Channel', $thread->channel);
    }

    /** @test */
    function thread_can_create_thread_path()
    {
    	$thread = create('App\Thread');
    	$this->assertEquals("/threads/{$thread->channel->slug}/{$thread->id}", $thread->path());
    }
}
