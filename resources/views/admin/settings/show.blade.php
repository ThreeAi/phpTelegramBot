@extends('layouts.admin')
@section('title', 'ShowSetting')
@section('content')
    <table class="table" class="mb-3">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">MoodleUrl</th>
            <th scope="col">MoodleToken</th>
            <th scope="col">TelegramToken</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row">{{$setting->id}}</th>
            <td>{{$setting->moodle_url}}</td>
            <td>{{$setting->moodle_token}}</td>
            <td>{{$setting->telegram_token}}</td>
        </tr>
        </tbody>
    </table>
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a href="{{route('admin.settings.index')}}" class="btn btn-primary" role="button" data-bs-toggle="button">Back</a>
        <a href="{{route('admin.settings.edit', $setting->id)}}" class="btn btn-warning" role="button" data-bs-toggle="button">Edit</a>
        <form action="{{route('admin.settings.destroy', $setting->id)}}" method="post">
            @csrf
            @method('delete')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>
@endsection
