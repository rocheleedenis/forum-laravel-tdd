@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
    <script>
        window.thread = <?= json_encode($thread) ?>;
    </script>
@endsection

@section('content')
    <thread-view :thread="{{ $thread }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="level">
                                <img class="mr-2" src="{{ $thread->creator->avatar_path }}" with="30" height="30">

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

                            <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}" v-if="signedIn"></subscribe-button>

                            <button class="btn btn-default"
                                    v-if="authorize('isAdmin')"
                                    @click="toggleLock"
                                    v-text="locked ? 'Unlock' : 'Lock'"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
