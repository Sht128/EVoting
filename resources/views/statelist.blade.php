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
            <button class="btn btn-primary"><a href="{{ url()->previous() }}">Back to Previous</a></button>
            <div class="election-progress">
                <div class="election-progress-title">
                    <p>All State List</p>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>State Name</th>
                            <th>View Voter Race Analytics</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($states as $state)
                        <tr>
                        <td>{{ $state->stateId }}</td>
                        <td><a href="{{ route('stateVoterRace',['stateId' => $state->stateId]) }}">View</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
</body>
</html>
