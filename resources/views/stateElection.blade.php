<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>State Election Page</title>

</head>
<body>
@include('headerhome')
    @if(Auth::user()->stateVoteStatus == 0)
    <div class="contentcontainer">
        <div class="card">
            <h2>District: {{ Auth::user()->stateConstituency }}</h2>
            <ul>
                @foreach ($candidatesList as $candidateList)
                    <li>
                        <img class="card-img-top" src="{{ asset('logo')}}/{{$candidateList->party}}.png"/>
                        <div class="card-header">{{ $candidateList->name }}</div>
                        <div class="card-body text-left candidateList">
                            <p>Candidate Party: {{ $candidateList->party}}</p>
                        </div>
                        <a href="{{ route('voteConfirmation', ['ic' => $candidateList->ic, 'electionType'=> 'State Election']) }}" class="btn">I want to Vote</a>
                    <li>
                @endforeach
            </ul>    
            </div>
        </div>
    </div>
    @elseif(Auth::user()->stateVoteStatus == 1)
    <div class="vote-profile" style="align:center">
        <h2>Vote Casted!</h2>
        <p>It seems you have already cast your vote for this election</p>
        <span><p>You are not allow to cast any vote continuing further, but you are able to check on current election progress provided by our voting system</p>
        <button class="btn-primary"><a href="{{ route('viewelectionprogress') }}">View Election Progress</a></button>
    </div>
    @endif
    
</body>
</html>