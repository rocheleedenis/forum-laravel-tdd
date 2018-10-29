@forelse($threads as $thread)
    <div class="card">
        <div class="card-header">
            <div class="level">
                <div class="flex">
                    <h5>
                        <a href="{{ $thread->path() }}">
                            @if(auth()->check() && $thread->hasUpdatedFor(auth()->user()))
                                <strong>
                                    {{ $thread->title }}
                                </strong>
                            @else
                                {{ $thread->title }}
                            @endif
                        </a>
                    </h5>

                    <p class="m-0">
                        Posted by:
                        <a href="/profiles/{{ $thread->creator->name }}">
                            {{ $thread->creator->name }}
                        </a>
                    </p>
                </div>

                <a href="{{ $thread->path() }}">
                    {{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}
                </a>
            </div>
        </div>

        <div class="card-body">
            <article>
                <div class="body">{{ $thread->body }}</div>
            </article>
        </div>

        <div class="card-footer text-muted">
            {{ $thread->visits()->count() }} visits
        </div>
    </div>
@empty
    <p>There are no relevant results at this time.</p>
@endforelse
