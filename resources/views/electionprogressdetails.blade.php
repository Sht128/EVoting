<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>Election District Details Page</title>
</head>

<body>
    @include ('headerhome')
    <div class="contentcontainer">
        <div class="election-progress">
            <table class="election-progress">
                <thead>
                    <tr>
                        <th>Election Districts</th>
                        <th>Total Voter Count</th>
                        <th>Current Vote Count</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($districts as $district)
                    <tr>
                        <td>{{ $district->districtId}}</td>
                        <td>{{ $district->voterTotalCount}}</td>
                        <td>{{ $district->currentVoteCount}}</td>
                        <td><a href="{{ route('electionprogress', ['districtid' => $district->districtId, 'election' => $electiontype]) }}">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
        </div>
    </div>
