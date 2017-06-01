<div class="panel panel-default">
	<div class="panel-heading">
		<div class="level">
			<h5 class="flex">
				<a href="#">{{ $reply->owner->name }}</a> said {{ $reply->created_at->diffForHumans() }}...
			</h5>

			<div>
				<?php $faves = $reply->favorites->count(); ?>
				<form method="POST" action="/replies/{{ $reply->id }}/favorites">
					{{ csrf_field() }}
					<button type="submit" class="btn btn-default" {{ $reply->isFavorited(auth()->id()) ? 'disabled' : '' }}>
						{{ $faves }} {{ str_plural('Favorite', $faves) }}
					</button>
				</form>
			</div>

		</div>
	</div>
	<div class="panel-body">
		{{ $reply->body }}
	</div>
</div>