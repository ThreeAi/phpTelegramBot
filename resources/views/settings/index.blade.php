@extends('layouts.main')
@section('content')
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">MoodleUrl</th>
        </tr>
        </thead>
        <tbody>
        @foreach($settings as $setting)
            <tr>
                <th scope="row">{{$setting->id}}</th>
                <td><a href="{{route('settings.show', $setting->id)}}"> {{$setting->moodle_url}} </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="mb-3">
        <a href="{{route('settings.create')}}" class="btn btn-primary" role="button" data-bs-toggle="button">Add new</a>
    </div>
@endsection
