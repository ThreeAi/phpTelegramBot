@extends('layouts.admin')
@section('title', 'Users')
@section('content')
    <div>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">UserId</th>
                <th scope="col">UserName</th>
                <th scope="col">MoodleId</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <th scope="row">{{$user->id}}</th>
                    <th scope="row">{{$user->user_id}}</th>
                    <th scope="row">{{$user->username}}</th>
                    <th scope="row">{{$user->moodle_id}}</th>
                </tr>
            @endforeach
            </tbody>
            </table>
    </div>
@endsection
