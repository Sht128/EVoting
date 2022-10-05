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
@include('headerhome')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Welcome, {{ Auth::user()->name}}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}

                    <p class="card title">Your Vote Eligibility Status indicates that you still have
                        @if (Session('userVoteCount') == 0)
                            0
                        @elseif (Session('userVoteCount') == 1)
                            1
                        @else
                            2
                        @endif
                        votes left</p>

                    @if (Session('userVoteCount') > 0)
                    <p class="card text"> You are able to cast your remaining votes through our Election page:</p>
                    @endif
                    <div class="card text-center">
                        @if (Auth::user()->parlimentVoteStatus == 0)
                            <a href="{{ route('federalElectionPage') }}">Cast your parlimental vote here!</a>
                        @endif
                        @if (Auth::user()->stateVoteStatus == 0)
                            <a href="{{ route('stateElectionPage') }}">Cast your state vote here!</a>
                        @endif
                    </div>    
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>