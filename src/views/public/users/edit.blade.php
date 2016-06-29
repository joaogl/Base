@extends('layouts.default')

{{-- Page title --}}
@section('title')
    User account
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
            <h3>My Account</h3>
        </div>
        <div class="row">
            <div class="row">
                <div class="col-md-12">
                    <!--main content-->
                    <div class="position-center">

                        @include('errors.list')

                        <div>
                            <h3 class="text-primary">Personal Information</h3>
                        </div>

                        {!! Form::model($user, array('route' => array('profile'), 'files' => true, 'class' => 'form-horizontal')) !!}

                            <div class="form-group">
                                <label class="col-md-2 control-label">Avatar:</label>
                                <div class="col-md-2">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail img-max img-rounded" style="text-align:center">
                                            @if($user->pic)
                                                <img src="{!! url('/').'/uploads/users/'.$user->pic !!}" alt="img" class="img-max img-rounded"/>
                                            @else
                                                <img src="http://placehold.it/200x200" alt="..." class="img-max img-rounded" />
                                            @endif
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail img-max img-rounded"></div>
                                        <div>
                                            <span class="btn btn-primary btn-file">
                                                <span class="fileinput-new">Select image</span>
                                                <span class="fileinput-exists">Change</span>
                                                <input type="file" name="pic" id="pic" />
                                            </span>
                                            <a href="#" class="btn btn-primary fileinput-exists" data-dismiss="fileinput">Remove</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 control-label">
                                    Username:
                                </label>
                                <div class="col-lg-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-fw fa-user-md text-primary"></i>
                                        </span>
                                        <input type="text" disabled placeholder="Your username" name="first_name" id="u-name" class="form-control" value="{!! Input::old('username', $user->username) !!}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group {{ $errors->first('first_name', 'has-error') }}">
                                <label class="col-lg-2 control-label">
                                    First Name:
                                    <span class='require'>*</span>
                                </label>
                                <div class="col-lg-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-fw fa-user-md text-primary"></i>
                                        </span>
                                        <input type="text" placeholder="Your first name" name="first_name" id="u-name" class="form-control" value="{!! Input::old('first_name', $user->first_name) !!}"></div>
                                    <span class="help-block">{{ $errors->first('first_name', ':message') }}</span>
                                </div>
                            </div>

                            <div class="form-group {{ $errors->first('last_name', 'has-error') }}">
                                <label class="col-lg-2 control-label">
                                    Last Name:
                                    <span class='require'>*</span>
                                </label>
                                <div class="col-lg-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-fw fa-user-md text-primary"></i>
                                        </span>
                                        <input type="text" placeholder=" " name="last_name" id="u-name" class="form-control" value="{!! Input::old('last_name',$user->last_name) !!}"></div>
                                    <span class="help-block">{{ $errors->first('last_name', ':message') }}</span>
                                </div>
                            </div>

                            <div class="form-group {{ $errors->first('birthday', 'has-error') }}">
                                <label class="col-lg-2 control-label">
                                    Birthday:
                                </label>
                                <div class="col-lg-6">
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-fw fa-calendar text-primary"></i>
                                            </span>
                                        {!!  Form::text('birthday', Input::old('birthday', $user->birthday != null ? $user->birthday->format('d/m/Y') : ''), array('id' => 'datepicker','class' => 'form-control datepicker', 'data-date-format'=> 'yyyy/mm/dd', 'data-provide' => 'datepicker', 'maxlength' => '10'))  !!} </div>
                                    <span class="help-block">{{ $errors->first('birthday', ':message') }}</span>
                                </div>
                            </div>

                            <div class="form-group {{ $errors->first('email', 'has-error') }}">
                                <label class="col-lg-2 control-label">
                                    Email:
                                    <span class='require'>*</span>
                                </label>
                                <div class="col-lg-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-fw fa-envelope text-primary"></i>
                                        </span>
                                        <input type="text" placeholder=" " id="email" name="email" class="form-control" value="{!! Input::old('email',$user->email) !!}"></div>
                                    <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                                </div>

                            </div>

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
                                <label class="col-lg-2 control-label">Gender: </label>
                                <div class="col-lg-6">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="gender" value="0" @if($user->gender == 0) checked="checked" @endif />
                                            Male
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="gender" value="1" @if($user->gender == 1) checked="checked" @endif />
                                            Female
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="gender" value="2" @if($user->gender == 2) checked="checked" @endif />
                                            Other
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 control-label">
                                    Description:
                                </label>
                                <div class="col-lg-6">
                                    <textarea rows="5" cols="30" class="form-control" id="description" name="description">{!! Input::old('description', $user->description) !!}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
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
