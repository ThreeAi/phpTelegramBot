<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>@yield('title')</title>
</head>
<body>
<div class="container">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <div class="collapse navbar-collapse">
                    <div class="navbar-nav">
                        <a class="nav-link active" aria-current="page" href="{{route('admin.settings.index')}}">Settings</a>
                        <a class="nav-link" aria-current="page" href="{{route('admin.users.index')}}">Users</a>
                        <a class="btn btn-outline-danger" href="{{ route("admin.logout") }}">Logout</a>
                    </div>
                </div>
            </div>
        </nav>
    @yield('content')
</div>
</body>
</html>
