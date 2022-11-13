<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>Voter Profile Page</title>

</head>
<body>
    @include('headerhome')
    <div class="contentcontainer">
        
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Verify Your Vote Authentication Code') }}</div>
    
                    <div class="card-body">
                    {{ __('Before proceeding, please check your email for a verification code. You are required to authenticate your vote by providing the verification code sent.') }}<br><br>

                        <form method="POST" action="{{ route('authenticateCode',['ic'=>$ic,'electionType'=>$electionType]) }}">
                                @csrf
                                <div class="row mb-3">
                                    <label for="verify" class="col-md-4 col-form-label text-md-end">{{ __('Verify Text Code') }}</label>

                                    <div class="col-md-4">
                                        <input id="verify" type="text" class="form-control" name="verify" required autocomplete="new-password">
                                    </div>
                                </div>
                                
                                <div class="row mb-0">
                                    <div class="col-md-6 offset-md-3">
                                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Verify') }}
                                        </button>
                                    </div>
                                </div>
                            </form>

                        <br><br>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>