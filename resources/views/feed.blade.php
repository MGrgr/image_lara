@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-start">
        <div class="col-md-3 sticky-top">
            <div class="card mb-3">
                <div class="card-header">
                    <div class="font-weight-bold w-50 my-auto">
                        Filter
                    </div>
                </div>
                <div class="card-body">
                @if (\Request::is('feed'))
                <form id="form" action="{{route('feed')}}" method="GET">
                    @CSRF
                    <div class="form-group">
                        <label for="description">Description like a</label>
                        <input value="{{old('description')}}" type="text" class="form-control" name="description" id="description" placeholder="Description">
                    </div>
                    <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">User age</span>
                        </div>
                        <input value="{{old('age_from')}}" type="number" aria-label="Age from" name="age_from" min='0' max='200' class="form-control" placeholder='Min'>
                        <input value="{{old('age_to')}}" type="number" aria-label="Age to" name="age_to"  min='0' max='200' class="form-control" placeholder='Max'>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select class="custom-select" id="gender" name="gender">
                            <option value="">Choose...</option>
                            <option value="1">Female</option>
                            <option value="0">Male</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="{{route('feed')}}"><button class="btn btn-warning">Reset</button></a>
                </form>
                @endif
                @if (\Request::is('my')) 
                <form id="form" action="{{route('my')}}" method="GET">
                    @CSRF
                    <div class="form-group">
                        <label for="filter">Type of post</label>
                        <select class="custom-select" id="filter" name="filter">
                            <option value="">Choose...</option>
                            <option value="1">Published</option>
                            <option value="2">Not published</option>
                            <option value="3">Scheduled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="{{route('my')}}"><button class="btn btn-warning">Reset</button></a>
                </form>
                @endif
                </div>
            </div>
        </div>
        <div class="col-md-8">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        @auth
        <a class="w-100" href="{{route('images.create')}}">
        <button class="btn btn-success w-100 mb-3 font-weight-bold">+ Create a new image</button>
        </a>
        @endauth
        @if ($images->count() > 0)
        @foreach ($images as $image)
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between">
                    <div class="font-weight-bold w-50 my-auto">
                        {{$image->user->name}}
                    </div>
                    @can('update', $image)
                    <div class="text-right d-flex justify-content-end w-50">
                        @if (\Request::is('my'))
                        <div class="text-muted my-auto">{{$image->published ? ( $image->published_at > Carbon\Carbon::now() ? 'Waiting for '.$image->published_at : 'Published') : 'Not published'}} </div>
                        @endif
                        <form  action="{{route('images.destroy', $image->id )}}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button class="btn btn-small btn-danger ml-3" type="submit">Delete</button>
                        </form>
                        <a href="{{route('images.edit', $image->id)}}"><button class="ml-1 btn btn-small btn-primary">Edit</button></a>
                    </div>
                    @endcan
                </div>
                <div class="card-body">
                    <img class="card-img-top" src="{{route('images.show',$image->id)}}" alt="">
                    <div class="alert alert-secondary text-left mt-3">{{$image->description}}</div>
                </div>
            </div>
        @endforeach
        @else
        <div class="card">
                <div class="card-header">Feed</div>
                <div class="card-body">
                    <h1 class="text-center">No data to view</h1>
                </div>
            </div>
        @endif
        </div>
    </div>
</div>
@endsection