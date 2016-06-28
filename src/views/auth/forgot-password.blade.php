@extends('layouts.default')

{{-- Page title --}}
@section('title')
    Forgot password
    @parent
@endsection

{{-- Page content --}}
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Forgot password</div>
                    <div class="panel-body">

                        <!-- Notifications -->
                        @include('layouts.notifications')

                        {!! Form::open(array('url' => route('forgot-password'), 'method' => 'post')) !!}

                        <p>
                            Enter your email address below and we'll send a special reset password link to your inbox.
                        </p>

                        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                            {!! Form::text('email', null, array('class' => 'form-control input-lg', 'required' => 'required|email', 'placeholder' => 'your@email.com')) !!}
                            <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <p class="login button" style="text-align: center">
                                    <a href="{{ route('login') }}">
                                        <button type="button" class="btn btn-responsive btn-warning btn-sm">Back</button>
                                    </a>
                                </p>
                            </div>
                            <div class="col-md-6" style="text-align: center">
                                <input type="submit" value="Submit" class="btn btn-success" />
                            </div>
                        </div>

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
