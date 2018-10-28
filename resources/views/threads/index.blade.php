@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @include('threads._list')

                {{ $threads->render() }}
            </div>

            <div class="col-md-4">
            	@if(count($trending))
					<div class="card">
				        <div class="card-header">
							Trending Threads
				        </div>
				        <div class="card-body">
				        	<div class="list-group-flush">
		        				@foreach ($trending as $thread)
				        			<a class="list-group-item list-group-item-action" href="{{ $thread->path }}">
				        				{{ $thread->title }}
				        			</a>
					        	@endforeach
				        	</article>
				        </div>
				    </div>
				@endif
            </div>
        </div>
    </div>
@endsection
