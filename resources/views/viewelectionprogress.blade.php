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
    @include('headerhome')

    <div class="contentcontainer">
        <p>Election Vote Progress Page</p>
        <div class="card text-center">
            <div class="card-header">
                <p> You are able to choose which type of Election Progress to view:</p>
            </div>
            <div class="card-body">
                <p>Election Progress Currently Available:</p>
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
                            <td>Federal Election</td>
                            <td>
                                @if($parliment == 'Ongoing')
                                Ongoing
                                @elseif($parliment == 'Done')
                                Done
                                @endif
                                
                            </td>
                            <td>
                                <a href="{{route('parlimentelectionprogress')}}" class="nav-item">View</a>
                            </td>
                        </tr>
                        <tr>
                            <td>State Election</td>
                            <td>
                                @if($state == 'Ongoing')
                                Ongoing
                                @elseif($state == 'Done')
                                Done
                                @endif
                            </td>
                            <td>
                                <a href="{{route('stateelectionprogress')}}" class="nav-item">View</a>
                            </td>
                        </tr>
                    </tbody>
                </div>
            </div>
        </div>
    </div>
</body>