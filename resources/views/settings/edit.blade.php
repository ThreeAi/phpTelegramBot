@extends('layouts.main')
@section('content')
    <div>
        <form action="{{ route('settings.update', $setting->id) }}" method="post">
            @csrf
            @method('patch')
            <div class="mb-3">
                <label for="telegram_token">telegram_token</label>
                <input type="text" name="telegram_token" class="form-control" id="telegram_token" placeholder="telegram_token", value="{{$setting->telegram_token}}">
            </div>
            <div class="mb-3">
                <label for="moodle_token">moodle_token</label>
                <input type="text" name="moodle_token" class="form-control" id="moodle_token" placeholder="moodle_token", value="{{$setting->moodle_token}}">
            </div>
            <div class="mb-3">
                <label for="moodle_url">moodle_url</label>
                <input type="text" name="moodle_url" class="form-control" id="moodle_url" placeholder="moodle_url", value="{{$setting->moodle_url}}">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection

