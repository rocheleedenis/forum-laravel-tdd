@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row justify-content-center">
	        <div class="col-md-8">
			    <div class="page-heading">
			        <h1>{{ $profileUser->name }}</h1>
			        <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
			        <hr>
			        @can('update', $profileUser)
			        	<form action="{{ route('avatar', $profileUser) }}" method="post" enctype="multipart/form-data">
			        		{{csrf_field()}}
							<input type="file" name="avatar">
							<button type="submit" class="btn btn-primary">Add avatar</button>
			        	</form>
			        @endcan

			        <img src="{{ asset($profileUser->avatar_path) }}" with="50" height="50">
			    </div>

			    @forelse($activities as $date => $activity)
					<br>
			    	<h4>{{ $date }}</h4>
			    	<hr>
			    	@foreach($activity as $record)
						@if(view()->exists("profiles.activities.{$record->type}"))
							@include ("profiles.activities.{$record->type}", ['activity' => $record])
						@endif
		        	@endforeach
		        @empty
		        	<p>There is no activity for this user yet.</p>
		        @endforelse
		    </div>
		</div>
	</div>
@endsection
