@extends('layouts/default')

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
                        @include('layouts/notifications')

                        {!! Form::open(array('url' => route('forgot-password-confirm',compact(['userId','passwordResetCode'])), 'method' => 'post')) !!}

                            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                {!! Form::password('password', array('class' => 'form-control input-lg JQMaxLength', 'required' => 'required|between:3,32', 'placeholder' => 'Your new password', 'maxlength' => 30)) !!}
                                <span class="help-block">{{ $errors->first('password', ':message') }}</span>
                            </div>

                            <div class="form-group {{ $errors->has('password_confirm') ? 'has-error' : '' }}">
                                {!! Form::password('password_confirm', array('class' => 'form-control input-lg JQMaxLength', 'required' => 'required|same:password', 'placeholder' => 'Confirm your new password', 'maxlength' => 30)) !!}
                                <span class="help-block">{{ $errors->first('password_confirm', ':message') }}</span>
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
