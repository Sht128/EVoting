<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Dashboard</title>

     <!-- bootstrap core css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/dashboard.css') }}"/>

    <div id="sidebar" class="sidebar">
        <p class="sidebar-title">E-Voting Analytical Dashboard</p><span><br><br><br><br><br><br>
          <button class="dropdownbtn">Elections &#9660;</button>
          <div class="dropdown-container">
              <a href="{{ route('allelections') }}" class="nav-link">All Elections Analytics</a>
              <a class="nav-link" href="{{ route('electionresults') }}">Elections Result</a>
          </div>
            <button class="dropdownbtn">Voter &#9660;</button>
            <div class="dropdown-container">
                <a href="{{ route('allVoterAnalytics') }}" class="nav-link">All Voters Analytics</a>
                <a class="nav-link" href="{{ route('raceanalytic') }}">Voter Race Analytics</a>
            </div>
            <button class="dropdownbtn">Candidate &#9660;</button>
            <div class="dropdown-container">
                <a href="{{ route('candidatelist') }}" class="nav-link">All Candidates List</a>
                <a href="{{ route('candidateparty') }}" class="nav-link">All Candidate Party Analytics</a>
                <a class="nav-link" href="{{ route('candidatedepositpage') }}">Candidate Deposit</a>
            </div>
            <a class="nav-link" href="{{ route('home') }}">Return to Voting System </a>
        
    </div>

<script>
    var dropdown = document.getElementsByClassName("dropdownbtn");
    var i;
    
    for (i = 0; i < dropdown.length; i++) {
      dropdown[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var dropdownContent = this.nextElementSibling;
        if (dropdownContent.style.display === "block") {
          dropdownContent.style.display = "none";
        } else {
          dropdownContent.style.display = "block";
        }
      });
    }
</script>