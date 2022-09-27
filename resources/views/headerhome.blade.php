<!DOCTYPE html>
<html lang="en">
    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>  
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}"> 
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">-->

    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #F7502C;">
        <div class="collpase navbar-collapse" id="navigation">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Home<span class="sr-only">(current) </span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="voteDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Vote</a>
                        <div class="dropdown-menu" aria-labelledby="voteDropDown">
                            <a class="dropdown-item" id="pvote" href="*">Federal Election</a>
                            <a class="dropdown-item" id="svote" href="*">State Election</a>
                        </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">View Election Progress</a>
                </li>
            <ul>
            
            <ul class="navbar-nav navbar-dashboard">
                <li class="nav-item">
                    <a class="nav-link" href="#"><?php echo('user')?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Logout</a>
                </li>
            </ul>
        </div>
    </nav> 
</html>