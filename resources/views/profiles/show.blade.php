@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row justify-content-center">
	        <div class="col-md-8">
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
				                    <a href="{{ route('profile', $thread->creator) }}" title="Ver perfil">
				                        {{ $thread->creator->name }}
				                    </a> posted:
				                    <a href="{{ $thread->path() }}" title="">
				                    	{{ $thread->title }}
				                    </a>
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
		</div>
	</div>
@endsection
