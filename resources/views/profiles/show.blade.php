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
			    @foreach($activities as $date => $activity)
					<br>
			    	<h4>{{ $date }}</h4>
			    	<hr>
			    	@foreach($activity as $record)
						@include ("profiles.activities.{$record->type}", ['activity' => $record])
		        	@endforeach
		        @endforeach
		    </div>
		</div>
	</div>
@endsection
