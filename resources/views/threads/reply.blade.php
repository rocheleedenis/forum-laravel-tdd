<div class="card">
    <div class="card-header">
        <div class="level">
		    <h5 class="flex">
		        <a href="#" title="Ver perfil">
		            {{ $reply->owner->name }}
		        </a> said {{ $reply->created_at->diffForHumans() }}...
		    </h5>
	        <div>
	        	<form action="/replies/{{ $reply->id }}/favorites" method="POST">
	        		@csrf
	        		<button type="submit" class="btn btn-default" {{ $reply->isFavorited() ? 'disabled' : '' }}>
	        			{{ $reply->favorites()->count() }} {{ 'Favorite', $reply->favorites()->count() }}
	        		</button>
	        	</form>
	        </div>
	    </div>
    </div>

    <div class="card-body">
        <div class="body">{{ $reply->body }}</div>
    </div>
</div>
