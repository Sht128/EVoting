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
        <p>{{$state}} Election District List</p><br>
        <hr>
        <div class="election-progress">
            <table class="election-progress">
                <thead>
                    <tr>
                        <th>District Name</th>
                        <th>Total Vote Count</th>
                        <th>Majority Candidate</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($states as $state)
                    <tr>
                        <td>{{ $state->districtId}}</td>
                        <td>{{ $state->currentVoteCount}}</td>
                        <td>{{ $state->name}}</td>
                        <td><a href="{{ route('electiondistrictsresult', ['districtId' => $state->districtId, 'electionType'=>'State Election']) }}">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br><br>
        </div>
    </div>

</body>
</html>