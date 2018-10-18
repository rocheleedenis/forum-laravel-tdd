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

                    <replies @added="repliesCount++" @removed="repliesCount--"></replies>
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

                            <p>
                                <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}"></subscribe-button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
