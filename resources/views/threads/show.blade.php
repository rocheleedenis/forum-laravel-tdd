@extends('layouts.app')

@section('content')
    <thread-view :initial-replies-count="{{ $thread->replies_count }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="level">
                                <div class="flex">
                                    <a href="{{ route('profile', $thread->creator->name) }}" title="See perfil">
                                        {{ $thread->creator->name }}
                                    </a> posted:
                                    <strong>{{ $thread->title }}</strong>
                                </div>
                                @can ('update', $thread)
                                    <form action="{{ $thread->path() }}" method="POST">
                                        @csrf
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="btn btn-link">Delete Thread</button>
                                    </form>
                                @endcan
                            </h5>
                        </div>

                        <div class="card-body">
                            <div class="body">{{ $thread->body }}</div>
                        </div>
                    </div>


                    <replies :data="{{ $thread->replies }}" @removed="repliesCount--"></replies>

                    {{ $replies->links() }} -->

                    @if (auth()->check())
                        <form action="{{ $thread->path() . '/replies' }}" method="post" style="margin: 0 15px;">
                            @csrf
                            <div class="form-group row">
                                <textarea name="body" class="form-control" placeholder="Have something to say?" rows="5"></textarea>
                            </div>
                            <button type="submit" class="btn btn-default" style="margin: 0 -15px;">Post</button>
                        </form>
                    @else
                        <p>Please <a href="{{ route('login') }}">sign in</a> to participate in this discussion.</p>
                    @endif
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Informations of thread
                        </div>

                        <div class="card-body">
                            <p>
                                This thread was publish {{ $thread->created_at->diffForHumans() }} by <a href="{{ route('profile', $thread->creator->name) }}" title="See perfil">{{ $thread->creator->name }}</a> and currently
                                has <span v-text="repliesCount"></span> {{ str_plural('comment', $thread->replies_count) }}.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
