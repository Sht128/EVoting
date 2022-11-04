<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>View Election Progress Page</title>

</head>
<body>
    @include('headerhome')

    <div class="contentcontainer">
        <div class="election-progress-title">
            <h1>Unofficial Election Results Page</h1>
        <p>State Election Results</p>
        <hr>
        <div class="election-progress">
            <table class="election-progress">
                <thead>
                    <tr>
                        <th>State Election State</th>
                        <th>Total District Count</th>
                        <th>Total Vote Count</th>
                        <th>Majority Party</th>
                        <th>View State Election Result</th>
                        <th>View State Districts Result</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($states as $state)
                    <tr>
                        <td>{{ $ongoingState->stateId}}</td>
                        <td>{{ $ongoingState->parliamentalDistrictCount}}</td>
                        <td>{{ $ongoingState->totalVoterCount}}</td>
                        <td>{{ $ongoingState->currentVoteCount}}</td>
                        <td><a href="{{ route('parliamentalelectionstate', ['ongoingstate' => $ongoingState->stateId]) }}">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br><br>
        </div>

        <p>Parliment Election Results</p><br>
        <hr>
        <div class="election-progress">
            <table class="election-progress">
                <thead>
                    <tr>
                        <th>Federal Election State</th>
                        <th>Total Districts Count</th>
                        <th>Majority Coalition</th>
                        <th>Majority Seats</th>
                        <th>View Full Districts List</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($doneStateLists as $ongoingState)
                    <tr>
                        <td>{{ $ongoingState->stateId}}</td>
                        <td>{{ $ongoingState->parliamentalDistrictCount}}</td>
                        <td>{{ $ongoingState->totalVoterCount}}</td>
                        <td>{{ $ongoingState->currentVoteCount}}</td>
                        <td><a href="{{ route('parliamentalelectionstate', ['ongoingstate' => $ongoingState->stateId]) }}">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
        </div>
    </div>
</body>