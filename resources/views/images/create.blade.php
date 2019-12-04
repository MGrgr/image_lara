@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
            <div class="card">
                <div class="card-header">Create a new picture</div>
                <div class="card-body">
                <img class="card-img-top" src="{{asset('storage/images/placeholder.png')}}" alt="Card image cap" id="image">
                <div class="input-group mt-3">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input"  name="image" id="image_upload">
                        <label for="image_upload" class="btn btn-primary">Browse</label>
                    </div>
                </div>
                <form action="{{route('images.store')}}" method="POST">
                    @CSRF
                    <input type="hidden" name="id" id="id" value=''>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" class="form-control" name="description" id="description" placeholder="Description">
                    </div>
                    <div class="form-group">
                        <label for="published_at">Published at:</label>
                        <input type="text" class="form-control" id="published_at" name="published_at" aria-describedby="published_atHelp" placeholder="Select if need to change">
                        <small id="published_atHelp" class="form-text text-muted">When your picture appears in feed</small>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="published" name="published">
                        <label class="form-check-label" for="published">Published</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Add to feed</button>
                </form>
                </div>
            </div>
    </div>
</div>
@endsection