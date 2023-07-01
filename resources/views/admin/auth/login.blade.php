@extends('layouts.app')

@section('title', 'Authorization')

@section('content')
    <div class="d-flex align-items-center justify-content-center">
        <h1>Admin panel</h1>
    </div>
        <div class="d-flex justify-content-center">
        <form method="POST" action="{{ route("admin.login_process") }}" class="form-label">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" name="email" class="form-control" id="email" placeholder="email">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="text" name="password" class="form-control" id="password" placeholder="password">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        </div>
@endsection
