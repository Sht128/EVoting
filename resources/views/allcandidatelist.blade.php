<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>Home Page</title>
</head>

<body>
    @include('headerdashboard')
    <div class="main">
        <h2>All Candidate List</h2>
        <div class="candidate-deposit-list">
            <table>
                <thead>
                    <tr>
                        <th>Candidate Name</th>
                        <th>Candidate State</th>
                        <th>Parliamental Seat</th>
                        <th>State Seat</th>
                        <th>Candidate Party</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($candidates as $candidate)
                    <tr>
                        <td>{{ $candidate->name}}</td>
                        <td>{{ $candidate->registeredState}}</td>
                        @if(!empty($candidate->parliamentalConstituency))
                            <td>{{ $candidate->parliamentalConstituency}}</td>
                        @else
                            <td>No Parliamental Seat Available</td>
                        @endif
                        @if(!empty($candidate->stateConstituency))
                            <td>{{ $candidate->stateConstituency}}</td>
                        @else
                            <td>No State Seat Available</td>
                        @endif
                        <td>{{ $candidate->party}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

