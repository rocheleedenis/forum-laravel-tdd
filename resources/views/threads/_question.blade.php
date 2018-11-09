{{-- Editing the question. --}}
<div class="card" v-if="editing">
    <div class="card-header">
        <h5 class="level">
            <input type="text" class="form-control" value="{{ $thread->title }}">
        </h5>
    </div>

    <div class="card-body">
        <div class="form-group">
            <textarea class="form-control">{{ $thread->body }}</textarea>
        </div>
    </div>

    <div class="card-footer">
        <div class="level">
            <button class="btn btn-info btn-sm" v-if="!editing" @click="editing = true">
                Edit
            </button>
            <button class="btn btn-primary btn-sm" v-if="editing">
                Update
            </button>
            <button class="btn btn-default btn-sm ml-2" @click="editing = false">
                Cancel
            </button>

            @can ('update', $thread)
                <form action="{{ $thread->path() }}" method="POST" class="ml-auto">
                    @csrf
                    {{ method_field('DELETE') }}
                    <button type="submit" class="btn btn-danger btn-sm">
                        Delete
                    </button>
                </form>
            @endcan
        </div>
    </div>
</div>

{{-- Viewing the question. --}}
<div class="card" v-else>
    <div class="card-header">
        <h5 class="level">
            <img class="mr-2" src="{{ $thread->creator->avatar_path }}" with="30" height="30">

            <div class="flex">
                <a href="{{ route('profile', $thread->creator->name) }}" title="See perfil">
                    {{ $thread->creator->name }}
                </a> posted:

                <strong>{{ $thread->title }}</strong>
            </div>
        </h5>
    </div>

    <div class="card-body">
        <div class="body">{{ $thread->body }}</div>
    </div>

    <div class="card-footer">
        <button class="btn btn-default btn-sm" @click="editing = true">
            Edit
        </button>
    </div>
</div>
