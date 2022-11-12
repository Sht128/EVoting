<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>Federal Election Progress Page</title>

</head>
<body>
    @include('headerhome')
    <div class="contentcontainer">
        <div class="election-progress-title">
        <p>Ongoing Parliment Election State</p>
        <hr>
        <div class="election-progress">
            <table class="election-progress">
                <thead>
                    <tr>
                        <th>Federal Election State</th>
                        <th>Total District Count</th>
                        <th>Total Voter Count</th>
                        <th>Current Vote Count</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($ongoingStateLists as $ongoingState)
                    <tr>
                        <td>{{ $ongoingState->stateId}}</td>
                        <td>{{ $ongoingState->parliamentalDistrictCount}}</td>
                        <td>{{ $ongoingState->totalVoterCount}}</td>
                        <td>{{ $ongoingState->currentVoteCount}}</td>
                        <td><a href="{{ route('parliamentalelectionstate', ['ongoingstate' => $ongoingState->stateId]) }}">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br><br>
        </div>

        <p>Finish Voted Parliment Election State</p><br>
        <hr>
        <div class="election-progress">
            <table class="election-progress">
                <thead>
                    <tr>
                        <th>Federal Election State</th>
                        <th>Total District Count</th>
                        <th>Total Voter Count</th>
                        <th>Current Vote Count</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($doneStateLists as $ongoingState)
                    <tr>
                        <td>{{ $ongoingState->stateId}}</td>
                        <td>{{ $ongoingState->parliamentalDistrictCount}}</td>
                        <td>{{ $ongoingState->totalVoterCount}}</td>
                        <td>{{ $ongoingState->currentVoteCount}}</td>
                        <td><a href="{{ route('parliamentalelectionstate', ['ongoingstate' => $ongoingState->stateId]) }}">View</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br><br><br>
        <button class="btn btn-primary"><a href="{{ url()->previous() }}">Back to Previous</button>
    </div>

<script>
    function openParlimentTab(evt, electionStatus){
        var i, tabContent, tabLinks;

        tabContent = document.getElementsByClassName('tabstate');
        for(i=0;i<tabContent.length;i++){
            tabContent[i].style.display = "none";
        }

        tabLinks = document.getElementsByClassName('tablinks');
        for(i=0;i<tabLinks.length;i++){
            tabLinks[i].className = tabLinks[i].className.replace(" active","");
        }

        document.getElementById(electionStatus).style.display="block";
        document.getElementsByClassName('tabparliment').style.display = "active";
        evt.currentTarget.className += "active";
    }

    function openStateTab(evt, electionStatus){
        var i, tabContent, tabLinks;

        tabContent = document.getElementsByClassName('tabparliment');
        for(i=0;i<tabContent.length;i++){
            tabContent[i].style.display = "none";
        }

        tabLinks = document.getElementsByClassName('tablinks');
        for(i=0;i<tabLinks.length;i++){
            tabLinks[i].className = tabLinks[i].className.replace(" active","");
        }

        document.getElementById(electionStatus).style.display="block";
        document.getElementsByClassName('tabstate').style.display = "active";
        evt.currentTarget.className += "active";
    }
</script>
</body>
