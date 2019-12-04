@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Profile</div>

                <div class="card-body">
                    Name: <span>{{ $user->name }}</span><br>
                    Email: <span>{{ $user->email }}</span><br>
                    Birthdate: <span>{{ $user->birthdate }}</span>
                    Gender: <span>{{ $user->gender ? 'Female' : 'Male' }}</span>
                    <div class="text-center">
                        <a href="{{route('feed')}}"><button class="btn btn-success">Go to feed list</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
