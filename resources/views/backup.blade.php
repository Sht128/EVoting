<div class="election-tab">
    <button class="tablinks" onclick="openParlimentTab(event,'federalongoing')">Ongoing</button>
    <button class="tablinks" onclick="openStateTab(event,'federaldone')">Done</button>
</div>

<div class="tabparliment">
    <div id="federalongoing" class="contentcontainer">
        <p> Please select Election State you want to view</p>
        <form action="{{ route('viewStateFederalElection') }}" method="POST">
            @csrf_token
            <label for="statename" class="col-md-4 col-form-label text-md-end">State: </label>
            <select onchange='this.form.submit()' name='statename' id='ongoingstate'>
                @foreach($ongoingStateLists as $state)
                <option value='{{$state->stateId}}' name='{{$state->stateId}}'>{{$state->stateId}}</option>
                @endforeach
            </select>
    </div>
</div>

<div class="tabstate">
    <div id="federaldone" class="contentcontainer">
        <p> Please select Election State you want to view</p>
        <form action="{{ route('viewStateFederalElection') }}" method="POST">
            @csrf_token
            <label for="statename" class="col-md-4 col-form-label text-md-end">State: </label>
            <select title="Please select a state" onchange='this.form.submit()' name='statename' id='donestate'>
                @foreach($doneStateLists as $state)
                <option value='{{$state->stateId}}' name='{{$state->stateId}}'>{{$state->stateId}}</option>
                @endforeach
            </select>
    </div>
</div>