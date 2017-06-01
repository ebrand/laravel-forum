@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2>{{ $thread->title }}</h2>
				</div>
				<div class="panel-body">
					{{ $thread->body }}
				</div>
			</div>

			@foreach($replies as $reply)
				@include('threads.reply')
			@endforeach

			{{ $replies->links() }}

			@if(auth()->check())
				<div class="row">
					<div class="col-md-8">
						<form method="POST" action="{{ $thread->path().'/replies' }}">
							{{ csrf_field() }}
							<div class="form-group">
								<textarea name="body" class="form-control" placeholder="Have something to add?" rows="5"></textarea>
							</div>
							<button type="submit" class="btn btn-default">Post Your Comment...</button>
						</form>
					</div>
				</div>
			@else
				<p class="text-center">Please <a href="{{ route('login') }}">sign-in</a> to participate in this discussion.</p>
			@endif

		</div>

		<div class="col-md-4">
			<div class="panel panel-default">
				<div class="panel-body">
					<p>
						This thread was published {{ $thread->created_at->diffForHumans() }} by
						<a href="#">{{ $thread->owner->name }}</a>, and currently has
						{{ $thread->replies_count }} {{ str_plural('comment', $thread->replies_count) }}.
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
