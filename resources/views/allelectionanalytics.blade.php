<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>All Voter Race Analysis Page</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    @include ('headerdashboard')
        <div class="main">
            <div class="election-progress">
                <div class="election-progress-title">
                    <p>Federal Districts List</p>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>District Name</th>
                            <th>State Name</th>
                            <th>Total Voter Count</th>
                            <th>Current Vote Count</th>
                            <th>Remaining Votes</th>
                            <th>Voting Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($parliamentals as $parliamental)
                        <tr>
                        <td>{{ $parliamental->districtId}}</td>
                        <td>{{ $parliamental->stateId}}</td>
                        <td>{{ $parliamental->voterTotalCount}}</td>
                        <td>{{ $parliamental->currentVoteCount}}</td>
                        <td>{{ $parliamental->remainingVote}}</td>
                            @if($parliamental->votingStatus == 0)
                            <td>Still Ongoing</td>
                            @else
                            <td>Finished Voting</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                    <br><br><br>
                    <div class="election-progress-title">
                    <p>State Districts List</p></div>
                <table>
                    <thead>
                        <tr>
                            <th>District Name</th>
                            <th>State Name</th>
                            <th>Total Voter Count</th>
                            <th>Current Vote Count</th>
                            <th>Remaining Votes</th>
                            <th>Voting Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($states as $state)
                        <tr>
                        <td>{{ $state->districtId}}</td>
                        <td>{{ $state->stateId}}</td>
                        <td>{{ $state->voterTotalCount}}</td>
                        <td>{{ $state->currentVoteCount}}</td>
                        <td>{{ $state->remainingVote}}</td>
                            @if($state->votingStatus == 0)
                            <td>Still Ongoing</td>
                            @else
                            <td>Finished Voting</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </div>
            </div>
</body>
</html>
