<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>Voter Profile Page</title>

</head>
<body>
    @include('headerhome')
        <h1>Vote History</h1>
        <div class="vote-history-federal">
            <h3>Federal Election Vote Profile</h3>
            @if(Auth::user()->parlimentVoteStatus == 0)
            <p>It seems you still have yet to cast your vote</p>
            <p>Your vote district is {{Auth::user()->parliamentConstituency}}</p>
            @elseif(Auth::user()->parlimentVoteStatus == 1)
                @if(!empty($federalCandidate))
                <p>Voter District: {{Auth::user()->parliamentConstituency}}</p>
                <p>Voted Candidate Name: {{$federalCandidate->name}}</p>
                <p>Voted Candidate Party: {{$federalCandidate->party}}</p>
                @endif
            @endif
        </div>

        <div class="vote-history-state">
            <h3>State Election Vote Profile</h3>
            @if(Auth::user()->stateVoteStatus == 0)
            <p>It seems you still have yet to cast your vote</p>
            <p>Your vote district is {{Auth::user()->stateConstituency}}</p>
            @elseif(Auth::user()->stateVoteStatus == 1)
                @if(!empty($stateCandidate))
                <p>Voter District: {{Auth::user()->stateConstituency}}</p>
                <p>Voted Candidate Name: {{$stateCandidate->name}}</p>
                <p>Voted Candidate Party: {{$stateCandidate->party}}</p>
                @endif
            @endif
        </div>