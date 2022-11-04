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
        <form class="d-inline" action="{{ route('candidatedepositfilter') }}" method="POST">
            @csrf
            <div class="row mb-3 ms-auto">
                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Filter: ') }}</label>

                <div class="col-md-4">
                    <select id="depositfilter" class="form-control" name="depositfilter" required autocomplete="new-password">
                        <option value="ongoing" name="ongoing">Ongoing Election Only</option>
                        <option value="done" name="done">Finished Election Only</option>
                        <option value="lostdeposit" name="lostdeposit">Lost Deposit</option>
                        <option value="parliamental" name="parliamental">Federal Election Only</option>
                        <option value="state" name="state">State Election Only</option>
                    </select>
                    
                </div>
                <button type="submit" class="btn btn-primary">Apply</button>
            </div>
        
        </form><br><br>

        <div class="candidate-deposit-list">
            <table>
                <thead>
                    <tr>
                        <th>Candidate Name</th>
                        <th>Parliamental Seat</th>
                        <th>State Seat</th>
                        <th>Parliamental Deposit</th>
                        <th>State Deposit</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($candidateList as $candidate)
                    <tr>
                        <td>{{ $candidate->name}}</td>
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
                        @if(!empty($candidate->parliamentElectionDeposit))
                            <td>{{ $candidate->parliamentElectionDeposit}}</td>
                        @else
                            <td>No Deposit Available</td>
                        @endif
                        @if(!empty($candidate->stateElectionDeposit))
                        <td>{{ $candidate->stateElectionDeposit}}</td>
                        @else
                            <td>No Deposit Available</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
