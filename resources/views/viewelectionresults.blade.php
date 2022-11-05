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
                        <td>{{ $state->stateId}}</td>
                        <td>{{ $state->stateDistrictCount}}</td>
                        <td>{{ $state->totalVoterCount}}</td>
                        <td>{{ $state->majorityCoalition}}</td>
                        <td><a href="{{ route('electionpartiesresult', ['stateId' => $state->stateId]) }}">View</a></td>
                        <td><a href="{{ route('statedistricts', ['stateId' => $state->stateId]) }}">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br><br>
        </div>

        <p>Parliment Election Results - {{ $federalElection }}</p><br>
        <hr>
            <div class="table-state-list">
                <table>
                    <thead>
                        <tr>
                            <th>Coalition Name</th>
                            <th>Acquired Seats Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($federalparties as $parties)
                        <tr>
                            <td>{{ $parties->party}}</td>
                            <td>{{ $parties->total}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="election-progress">
            <table class="election-progress">
                <thead>
                    <tr>
                        <th>Federal Election State</th>
                        <th>Total Vote Count</th>
                        <th>Majority Candidate</th>
                        <th>Majority Seats</th>
                        <th>View Full Districts List</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($parliments as $parliment)
                    <tr>
                        <td>{{ $parliment->districtId}}</td>
                        <td>{{ $parliment->currentVoteCount}}</td>
                        <td>{{ $parliment->majorityCandidate}}</td>
                        <td>{{ $parliment->majorityVote}}</td>
                        <td><a href="{{ route('electiondistrictsresult', ['districtId' => $district->districtId, 'electionType'=>'Federal Election']) }}">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
        </div>
    </div>
</body>