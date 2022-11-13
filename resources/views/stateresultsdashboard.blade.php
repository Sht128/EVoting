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
    @include('headerdashboard')

    <div class="main">
        <div class="election-progress-title">
            <h1>Unofficial State Election Results</h1>
        <p>State Election Results</p>
        <hr>
        <div class="election-progress">
            <table class="election-progress">
                <thead>
                    <tr>
                        <th>State Election State</th>
                        <th>Total Vote Count</th>
                        <th>Majority Party</th>
                        <th>View State Election Result</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($states as $state)
                    <tr>
                        <td>{{ $state->stateId}}</td>
                        <td>{{ $state->totalVoterCount}}</td>
                        <td>{{ $state->majorityCoalition}}</td>
                        <td><a href="{{ route('parties', ['stateId' => $state->stateId]) }}">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br><br>
        </div>
    </div>
</body>

</html>