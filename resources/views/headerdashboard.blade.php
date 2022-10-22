<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Dashboard</title>

     <!-- bootstrap core css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/dashboard.css') }}"/>

    <div id="sidebar" class="sidebar">
        <div class="sidebar-nav-item">
            <a class="nav-link" href="#">Election Progress</a>
        </div>
        <div class="sidebar-nav-item">
            <a class="nav-link" href="{{ route('candidatedepositpage') }}">Candidate Deposit</a>
        </div>
        <div class="sidebar-nav-item">
            <a class="nav-link" href="#">Voter Race Analytics</a>
        </div>
        <div class="sidebar-nav-item">
            <a class="nav-link" href="{{ route('home') }}">Return to Voting System </a>
        </div>
    </div>