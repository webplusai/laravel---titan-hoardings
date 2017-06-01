@extends('layouts.minimal')

@section('content')
    <div class="container">
        <div>
        <h2>Titan Hoardings Invitation</h2>
        </div>
        <h3>{{ ucwords($user->first_name.' '.$user->last_name) }}</h3>
        <h4>{{ $user->email }}</h4>
        <form class="m-t" role="form" method="POST" action="/invitations/accept/{{ $invitation->token }}">
            {!! csrf_field() !!}

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <input type="password" class="form-control" placeholder="Password" name="password" required="">
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" required="">
                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
            </div>

             <div class="form-group{{ $errors->has('policy') ? ' has-error' : '' }}">
                <input type="checkbox"  name="policy" >
                I agree to the <a href="/terms-conditions">Terms and Conditions</a> and <a href="/privacy-policy">Privacy Policy</a> 
                @if ($errors->has('policy'))
                    <span class="help-block">
                        <strong>{{ $errors->first('policy') }}</strong>
                    </span>
                @endif
            </div>

            <button type="submit" class="btn btn-primary block full-width m-b">Finish Setup</button>
        </form>
    </div>
@endsection
