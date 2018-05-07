@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <a href="#" title="Ver perfil">
                        {{ $thread->creator->name }}
                    </a> posted: {{ $thread->created_at->diffForHumans() }}...
                    {{ $thread->title }}
                </div>

                <div class="card-body">
                    <div class="body">{{ $thread->body }}</div>
                </div>
            </div>

            @foreach($thread->replies as $reply)
                @include ('threads.reply')
            @endforeach
        </div>
    </div>
</div>
@endsection
