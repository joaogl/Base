@extends('layouts.default')

{{-- Page title --}}
@section('title')
    Register
    @parent
@stop

{{-- Page content --}}
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Register</div>
                    <div class="panel-body">

                        <!-- Notifications -->
                        @include('layouts.notifications')

                        {!! Form::open(array('url' => route('register'), 'method' => 'post')) !!}

                            @foreach(Sentinel::createModel()->getRegisterFields() as $fieldid => $field)

                                <div class="form-group {{ $errors->has($fieldid) ? 'has-error' : '' }}">
                                    <label class="col-md-4 control-label">{{ $field['placeholder'] }}</label>

                                    <div class="col-md-6">
                                        @if ($field['type'] == 'text')
                                            @if (preg_match('/required/', $field['validator']))
                                                {!! Form::text($fieldid, null, array('class' => $field['classes'], 'required' => $field['validator'], 'placeholder' => $field['placeholder'], 'maxlength' => $field['maxlength'])) !!}
                                            @else
                                                {!! Form::text($fieldid, null, array('class' => $field['classes'], 'placeholder' => $field['placeholder'], 'maxlength' => $field['maxlength'])) !!}
                                            @endif
                                        @elseif($field['type'] == 'password')
                                            {!! Form::password($fieldid, array('class' => $field['classes'], 'required' => $field['validator'], 'placeholder' => $field['placeholder'], 'maxlength' => $field['maxlength'])) !!}
                                        @endif
                                        <span class="help-block">
                                            <strong>{{ $errors->first($fieldid, ':message') }}</strong>
                                        </span>
                                    </div>
                                </div>

                            @endforeach

                            <div class="form-group {{ $errors->has('g-recaptcha-response') ? 'has-error' : '' }}">
                                <label class="col-md-4 control-label">Confirm that you are not a bot</label>

                                <div class="col-md-6">
                                    <div class="g-recaptcha" align="center" data-sitekey="{{ env('RE_CAP_SITE') }}"></div>
                                    <span class="help-block">
                                        <strong>{{ $errors->first('g-recaptcha-response', ':message') }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-user"></i>Register
                                    </button>
                                </div>
                            </div>

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
