@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create a new thread</div>

                <div class="card-body">
                    <form action="/threads" method="POST" accept-charset="utf-8">
                        @csrf
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title">
                        </div>
                        <div class="form-group">
                            <label for="body">Body</label>
                            <textarea type="text" class="form-control" name="body" rows="8"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Publish</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
