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
        <form action="" method="POST">
            @csrf
            <div class="row mb-3">
                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Filter: ') }}</label>

                <div class="col-md-4">
                    <select id="depositfilter" class="form-control" name="depositfilter" required autocomplete="new-password">
                        <option value="ongoing" name="ongoing">Ongoing Election Only</option>
                        <option value="done" name="done">Finished Election Only</option>
                        <option value="lostdeposit" name="lostdeposit">Lost Deposit</option>
                    </select>
                </div>
            </div>
        <button type="submit" class="btn btn-primary center">Apply</button>
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
                        <td>{{ $candidate->parliamentalConstituency}}</td>
                        <td>{{ $candidate->stateConstituency}}</td>
                        <td>{{ $candidate->parliamentElectionDeposit}}</td>
                        <td>{{ $candidate->stateElectionDeposit}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
