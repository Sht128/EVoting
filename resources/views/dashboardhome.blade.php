<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>Home Page</title>
</head>

<body>
    @include('headerdashboard')
    <div class="main">
        <div class="card text-center">
                <div class="card-header"><p>Welcome, {{ Auth::user()->name}}</p></div>

                <div class="card-body">
                    {{ __('Admin Dashboard') }}
                </div>
        </div>
    </div>
</body>
