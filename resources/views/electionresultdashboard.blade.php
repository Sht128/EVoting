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
        <h2>Election Unofficial Results Page</h2>
        <div class="card text-center">
            <div class="card-header">
                <p> You are able to choose which type of Election Results to view:</p>
            </div>
            <div class="card-body">
                <p>Election Results Currently Available:</p>
                <div class="table-state-list">
                <table>
                    <thead>
                        <tr>
                            <th>Election Name</th>
                            <th>Election Status</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>State Election Summary</td>
                            <td>
                                @if($state == 'Ongoing')
                                Ongoing
                                @elseif($state == 'Done')
                                Done
                                @endif
                            </td>
                            <td>
                                <a href="{{route('stateresult')}}" class="nav-item">View</a>
                            </td>
                        </tr>
                        <tr>
                            <td>District Election Summary</td>
                            <td>
                                @if($parliment == 'Ongoing' && $state == 'Ongoing')
                                Ongoing
                                @elseif($parliment == 'Done' && $state == 'Done')
                                Done
                                @endif
                            </td>
                            <td><a href="{{ route('districtresult') }}" class="nav-item">View</a></td>
                        <tr>
                            <td>Federal Election Summary</td>
                            <td>
                                @if($parliment == 'Ongoing')
                                Ongoing
                                @elseif($parliment == 'Done')
                                Done
                                @endif
                            </td>
                            <td>
                                <a href="{{route('summary')}}" class="nav-item">View</a>
                            </td>
                        </tr>
                        
                    </tbody>
                </div>
            </div>
        </div>
    </div>
</body>