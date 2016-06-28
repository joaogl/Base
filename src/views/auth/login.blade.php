@extends('layouts.default')

{{-- Page title --}}
@section('title')
    Login
    @parent
@endsection

{{-- Page content --}}
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Login</div>
                    <div class="panel-body">

                        <!-- Notifications -->
                        @include('layouts.notifications')

                        {!! Form::open(array('url' => route('login'), 'method' => 'post')) !!}

                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                {!! Form::text('email', null, array('class' => 'form-control input-lg', 'required' => 'required', 'placeholder'=>'Your username or email name')) !!}
                                <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                            </div>

                            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                {!! Form::password('password', array('class' => 'form-control input-lg','required' => 'required', 'placeholder'=>'Your password')) !!}
                                <span class="help-block">{{ $errors->first('password', ':message') }}</span>
                            </div>

                            <!--<div class="form-group">
                                <p class="keeplogin">
                                    <input type="checkbox" name="remember-me" id="remember-me" value="remember-me" />
                                    <label for="remember-me">Keep me logged in</label>
                                </p>
                            </div>-->

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i>Login
                                </button>
                            </div>

                            <div class="form-group">
                                <a class="btn btn-link" href="{{ route('register') }}">Register</a>
                                <a class="btn btn-link" href="{{ route('forgot-password') }}">Forgot Your Password?</a>
                            </div>

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection