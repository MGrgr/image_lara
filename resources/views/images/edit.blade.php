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
                <div class="card-header">Edit a picture</div>
                <div class="card-body">
                <img class="card-img-top" src="{{route('images.show', $image->id)}}" alt="Card image cap" id="image">
                @if ( !($image->published && ($image->published_at < Carbon\Carbon::now() )) ) 
                <div class="input-group mt-3">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input"  name="image" id="image_upload">
                        <label for="image_upload" class="btn btn-primary">Browse</label>
                    </div>
                </div>
                @endif
                <form id="form" action="{{route('images.update', $image->id)}}" method="POST">
                    @CSRF
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input value="{{$image->description}}" type="text" class="form-control" name="description" id="description" placeholder="Description">
                    </div>
                    @if ( !($image->published && ($image->published_at < Carbon\Carbon::now() )) ) 
                    <div class="form-group">
                        <label for="published_at">Published at:</label>
                        <input value="{{$image->published_at}}" type="text" class="form-control" id="published_at" name="published_at" aria-describedby="published_atHelp" placeholder="Select if need to change">
                        <small id="published_atHelp" class="form-text text-muted">When your picture appears in feed</small>
                    </div>
                    <div class="form-group form-check">
                        <input {{$image->published ? 'checked' : ''}} type="checkbox" class="form-check-input" id="published" name="published">
                        <label class="form-check-label" for="published">Published</label>
                    </div>
                    @endif
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
                </div>
            </div>
    </div>
</div>
@endsection