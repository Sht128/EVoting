<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>State Election Progress Page</title>

</head>
<body>
    @include('headerhome')
    <div class="contentcontainer">
        <div class="election-progress-title">
        <p>Ongoing State Election State</p><br>
        <hr>
        <div class="election-progress">
            <table class="election-progress">
                <thead>
                    <tr>
                        <th>State Election State</th>
                        <th>Total District Count</th>
                        <th>Total Voter Count</th>
                        <th>Current Vote Count</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($ongoingStateLists as $ongoingState)
                    <tr>
                        <td>{{ $ongoingState->stateId}}</td>
                        <td>{{ $ongoingState->stateDistrictCount}}</td>
                        <td>{{ $ongoingState->totalVoterCount}}</td>
                        <td>{{ $ongoingState->currentVoteCount}}</td>
                        <td><a href="{{ route('stateelectionstate', ['ongoingstate' => $ongoingState->stateId]) }}">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br><br>
        </div>

        <p>Finish Voted State Election State</p><br>
        <hr>
        <div class="election-progress">
            <table class="election-progress">
                <thead>
                    <tr>
                        <th>State Election State</th>
                        <th>Total District Count</th>
                        <th>Total Voter Count</th>
                        <th>Current Vote Count</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($doneStateLists as $ongoingState)
                    <tr>
                        <td>{{ $ongoingState->stateId}}</td>
                        <td>{{ $ongoingState->stateDistrictCount}}</td>
                        <td>{{ $ongoingState->totalVoterCount}}</td>
                        <td>{{ $ongoingState->currentVoteCount}}</td>
                        <td><a href="{{ route('stateelectionstate', ['ongoingstate' => $ongoingState->stateId]) }}">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br><br><br>
        <button class="btn btn-primary"><a href="{{ url()->previous() }}">Back to Previous</button>
    </div>
