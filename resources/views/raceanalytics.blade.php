<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>Voter Race Analytics Page</title>
</head>

<body>
    @include('headerdashboard')
    <div class="main"> 
        <br><br>
        <div class="card text-center">
            <div class="card-header">
                <p> Types of Voter Race Analytics</p>
            </div>
            <div class="card-body">
                <p>Election Progress Currently Available:</p>
                <div class="table-race-analytics">
                <table>
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>All Voter Race</td>
                            <td><a href="{{ route('allVoterRace') }}">View</td>
                            </td>
                        </tr>
                        <tr>
                            <td>Voter Race By Districts</td>
                            <td><a href="{{ route('districtlist') }}">View</td>
                        <tr>
                            <td>Voter Race By States</td>
                            <td><a href="{{ route('statelist') }}">View</td>
                        </tr>
                        <tr>
                            <td>Voter Race By Voted Voters</td>
                            <td><a href="{{ route('votedVoterRace') }}">View</td>
                    </tbody>
                </div>
            </div>
        </div>

