@extends('layouts.app')

@section('content')
	<div class="container">
	    <div class="page-heading">
	        <h1>{{ $profileUser->name }}</h1>
	        <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
	        <hr>
	    </div>

	    @foreach($threads as $thread)
			<div class="card">
                <div class="card-header">
                	<div class="level">
                		<span class="flex">
		                    <a href="#" title="Ver perfil">
		                        {{ $thread->creator->name }}
		                    </a> posted: {{ $thread->created_at->diffForHumans() }}...
		                    {{ $thread->title }}
                		</span>

                		<span>{{ $thread->created_at->diffForHumans() }}</span>
                	</div>
                </div>

                <div class="card-body">
                    <div class="body">{{ $thread->body }}</div>
                </div>
            </div>
        @endforeach

        {{ $threads->links() }}
	</div>
@endsection