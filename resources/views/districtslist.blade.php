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
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Return to Previous Page</a>
            <div class="election-progress">
                <div class="election-progress-title">
                    <p>Federal Districts List</p>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>District Name</th>
                            <th>State Name</th>
                            <th>View Voter Race Analytics</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($parliamentals as $parliamental)
                        <tr>
                        <td>{{ $parliamental->districtId}}</td>
                        <td>{{ $parliamental->stateId}}</td>
                        <td><a href="{{ route('districtVoterRace',['districtId'=>$parliamental->districtId, 'electionType'=>'Federal Election'])}}">View</a></td>
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
                            <th>View Voter Race Analytics</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($states as $state)
                        <tr>
                        <td>{{ $state->districtId}}</td>
                        <td>{{ $state->stateId}}</td>
                        <td><a href="{{ route('districtVoterRace',['districtId'=>$state->districtId, 'State Election'])}}">View</a></td>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>

        </div>

