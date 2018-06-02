<div id="reply-{{ $reply->id }}" class="card">
    <div class="card-header">
        <div class="level">
		    <span class="flex">
		        <a href="{{ route('profile', $reply->owner->name) }}" title="Ver perfil">
		            {{ $reply->owner->name }}
		        </a> said {{ $reply->created_at->diffForHumans() }}...
		    </span>
	        <div>
	        	<form action="/replies/{{ $reply->id }}/favorites" method="POST">
	        		@csrf
	        		<button type="submit" class="btn btn-default" {{ $reply->isFavorited() ? 'disabled' : '' }}>
	        			{{ $reply->favorites_count }} {{ 'Favorite', $reply->favorites_count }}
	        		</button>
	        	</form>
	        </div>
	    </div>
    </div>

    <div class="card-body">
        <div class="body">{{ $reply->body }}</div>
    </div>

	@can('update', $reply)
	    <div class="card-footer">
	    	<form action="/replies/{{ $reply->id }}" method="POST">
	    		@csrf
	    		{{ method_field('DELETE') }}

	    		<button type="submit" class="btn btn-danger btn-xs">Delete</button>
	    	</form>
	    </div>
	@endcan
</div>
