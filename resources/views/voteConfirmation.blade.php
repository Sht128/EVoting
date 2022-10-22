<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>State Election Page</title>

</head>
<body>
@include('headerhome')
    <div class="contentcontainer">
        <h1>Vote Confirmation Page</h1>
        @if(Auth::user())
            <p class="candidateList"> You are voting for {{$seating}}, {{Auth::user()->state}}</p>
            <p class="candidateList"> Your vote will be submitted to candidate listed below, do you want to proceed?</p>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
            <a href="{{ route('castVote', ['ic'=> $candidate->ic, 'electionType' => $electionType]) }}"><button type="submit" class="btn">Confirm my Vote</button></a></br></br>
            <table class="table table-striped table-size">
                <thead>
                    <tr>
                    <th>Candidate</th>
                    <th>Candidate Info</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <td>Candidate Name</td>
                    <td>{{$candidate->name}}</td>
                    </tr>
                    <tr>
                    <td>Candidate Party</td>
                    <td>{{$candidate->party}}</td>
                    </tr>
                    <tr>
                    <td>Vote Type</td>
                    <td>{{$electionType}}</td>
                    </tr>
            </table>
        @endif
    </div>
</body>