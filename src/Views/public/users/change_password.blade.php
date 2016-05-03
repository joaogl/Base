@extends('layouts.default')

{{-- Page title --}}
@section('title')
    User password
    @parent
@endsection

{{-- page level styles --}}
@section('header_styles')

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datepicker/css/datepicker.css') }}">

@endsection

{{-- Page content --}}
@section('content')
    <div class="container">
        <div class="welcome">
            <h3>Change password</h3>
        </div>
        <div class="row">
            <div class="row">
                <div class="col-md-12">
                    <!--main content-->
                    <div class="position-center">

                        @include('errors.list')

                        {!! Form::model(null, array('route' => array('change-password'), 'class' => 'form-horizontal')) !!}

                        <div class="form-group {{ $errors->first('old-password', 'has-error') }}">
                            <p class="text-warning col-md-offset-2"><strong>If you don't want to change password... please leave them empty</strong></p>
                            <label class="col-lg-2 control-label">
                                Password:
                                <span class='require'>*</span>
                            </label>
                            <div class="col-lg-6">
                                <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-fw fa-key text-primary"></i>
                                            </span>
                                    <input type="password" name="old-password" placeholder=" " id="old-password" class="form-control"></div>
                                <span class="help-block">{{ $errors->first('old-password', ':message') }}</span>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->first('password', 'has-error') }}">
                            <label class="col-lg-2 control-label">
                                New password:
                                <span class='require'>*</span>
                            </label>
                            <div class="col-lg-6">
                                <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-fw fa-key text-primary"></i>
                                        </span>
                                    <input type="password" name="password" placeholder=" " id="password" class="form-control"></div>
                                <span class="help-block">{{ $errors->first('password', ':message') }}</span>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->first('password_confirm', 'has-error') }}">
                            <label class="col-lg-2 control-label">
                                Confirm Password:
                                <span class='require'>*</span>
                            </label>
                            <div class="col-lg-6">
                                <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-fw fa-key text-primary"></i>
                                                </span>
                                    <input type="password" name="password_confirm" placeholder=" " id="password_confirm" class="form-control"></div>
                                <span class="help-block">{{ $errors->first('password_confirm', ':message') }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                {!! Form::submit('Change password', ['class' => 'btn btn-primary']) !!}
                            </div>
                        </div>

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')

    <script type="text/javascript" src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script>
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy'
        });
    </script>

@endsection
