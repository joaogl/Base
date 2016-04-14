
{{-- page level styles --}}
@push('header_styles_stack')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datepicker/css/datepicker.css') }}">
@stop

<div class="form-group">
    <div class="row">
        <div class="col-md-2 {{ $errors->first('pic', 'has-error') }}">
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="text-align:center">
                    @if($user != null && $user->pic)
                        <img src="{!! url('/') . '/uploads/users/' . $user->pic !!}" alt="profile pic" class="img-max img-rounded"/>
                    @else
                        <img src="http://placehold.it/200x200" alt="..." />
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
            <span class="help-block">{{ $errors->first('pic', ':message') }}</span>
        </div>
        <div class="col-md-10">
            <div class="row">
                <div class="col-md-1 {{ $errors->first('gender', 'has-error') }}">
                    {!! Form::label('gender', 'Gender') !!}
                    {!! Form::select('gender', $genders, null, ['class' => 'form-control']) !!}
                    <span class="help-block">{{ $errors->first('gender', ':message') }}</span>
                </div>
                <div class="col-md-3 {{ $errors->first('first_name', 'has-error') }}">
                    {!! Form::label('first_name', 'First name') !!}
                    {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => 'First name']) !!}
                    <span class="help-block">{{ $errors->first('first_name', ':message') }}</span>
                </div>
                <div class="col-md-4 {{ $errors->first('last_name', 'has-error') }}">
                    {!! Form::label('last_name', 'Last name') !!}
                    {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'Last name']) !!}
                    <span class="help-block">{{ $errors->first('last_name', ':message') }}</span>
                </div>
                <div class="col-md-2 {{ $errors->first('username', 'has-error') }}">
                    {!! Form::label('username', 'Username') !!}
                    {!! Form::text('username', null, ['class' => 'form-control', 'placeholder' => 'Username']) !!}
                    <span class="help-block">{{ $errors->first('username', ':message') }}</span>
                </div>
                <div class="col-md-2 {{ $errors->first('birthday', 'has-error') }}">
                    {!! Form::label('birthday', 'Birthday') !!}
                    {!! Form::text('birthday', ($user != null && $user->birthday != null ? $user->birthday->format(config('jlourenco.support.Dates_Format')) : ''), ['class' => 'form-control JQCalendar', 'placeholder' => 'Birthday', 'maxlength' => '10']) !!}
                    <span class="help-block">{{ $errors->first('birthday', ':message') }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 {{ $errors->first('email', 'has-error') }}">
                    {!! Form::label('email', 'Email') !!}
                    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
                    <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                </div>
                <div class="col-md-2 {{ $errors->first('status', 'has-error') }}">
                    {!! Form::label('status', 'Status') !!}
                    {!! Form::select('status', $statusList, ($status != null ? $status : 0), ['class' => 'form-control']) !!}
                    <span class="help-block">{{ $errors->first('status', ':message') }}</span>
                </div>
                <div class="col-md-2 {{ $errors->first('created_at', 'has-error') }}">
                    {!! Form::label('created_at', 'Created at') !!}
                    {!! Form::text('created_at', ($user != null && $user->created_at != null ? $user->created_at->format(config('jlourenco.support.Dates_Format')) : ''), ['class' => 'form-control', 'readonly', 'placeholder' => 'Never']) !!}
                    <span class="help-block">{{ $errors->first('created_at', ':message') }}</span>
                </div>
                <div class="col-md-2 {{ $errors->first('ip', 'has-error') }}">
                    {!! Form::label('ip', 'Last ip') !!}
                    {!! Form::text('ip', null, ['class' => 'form-control', 'readonly', 'placeholder' => 'None']) !!}
                    <span class="help-block">{{ $errors->first('ip', ':message') }}</span>
                </div>
                <div class="col-md-2 {{ $errors->first('last_login', 'has-error') }}">
                    {!! Form::label('last_login', 'Last login') !!}
                    {!! Form::text('last_login', ($user != null && $user->last_login != null ? $user->last_login->format(config('jlourenco.support.DateTime_Format')) : ''), ['class' => 'form-control', 'readonly', 'placeholder' => 'Never']) !!}
                    <span class="help-block">{{ $errors->first('last_login', ':message') }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 {{ $errors->first('password', 'has-error') }}">
                    {!! Form::label('password', 'Password') !!}
                    <div class="input-group">
                        {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
                        <span class="input-group-addon">
                            <i class="fa fa-eye"></i>
                            <input type="checkbox" id="check_see" aria-label="See password" title="See password">
                        </span>
                    </div>
                    <span class="help-block">{{ $errors->first('password', ':message') }}</span>
                </div>
                <div class="col-md-4 {{ $errors->first('password_confirm', 'has-error') }}">
                    {!! Form::label('password_confirm', 'Confirm password') !!}
                    {!! Form::password('password_confirm', ['class' => 'form-control', 'placeholder' => 'Confirm password']) !!}
                    <span class="help-block">{{ $errors->first('password_confirm', ':message') }}</span>
                </div>
                <div class="col-md-4 {{ $errors->first('generate_password', 'has-error') }}">
                    {!! Form::label('generate_password', 'Generate password') !!}
                    <div class="row">
                        <div class="col-md-6">
                            {{ Form::submit('Generate', ['class' => 'btn btn-primary form-control', 'id' => 'generate_password']) }}
                        </div>
                        <div class="col-md-6">
                            {{ Form::submit('Clear', ['class' => 'btn btn-primary form-control', 'id' => 'clear_password']) }}
                        </div>
                    </div>
                    <span class="help-block">{{ $errors->first('generate_password', ':message') }}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 {{ $errors->first('force_new_password', 'has-error') }}">
                    {!! Form::label('force_new_password', 'Force new password after login') !!}
                    {{ Form::checkbox('force_new_password') }}
                    <br>
                    {!! Form::label('send_new_password_email', 'Send new password to users email') !!}
                    {{ Form::checkbox('send_new_password_email') }}
                    <span class="help-block">{{ $errors->first('force_new_password', ':message') }}</span>
                </div>
                <div class="col-md-4">
                    <small id="password-text"></small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-12 {{ $errors->first('description', 'has-error') }}">
            {!! Form::label('description', 'Description') !!}
            {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Description']) !!}
            <span class="help-block">{{ $errors->first('description', ':message') }}</span>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-offset-10 col-md-2">
            {!! Form::submit($submitButton, ['class' => 'btn btn-primary form-control']) !!}
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-offset-1 col-md-4">
        </div>
    </div>
</div>


{{-- page level scripts --}}
@push('footer_scripts_stack')

    <script type="text/javascript" src="{{ asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script>
        $('#generate_password').on('click', function(event){
            event.preventDefault();

            // Customize the size
            var sizeFrom = 15;
            var sizeTo = 20;


            // System
            var floatingPoint = Math.floor(Math.random() * 5) + 1;
            var minSys = sizeFrom + floatingPoint;
            var maxSys = sizeTo + floatingPoint;
            var randomSize = Math.floor(Math.random() * (maxSys - minSys)) + minSys;

            $('#password, #password_confirm').val(randomString(maxSys, '#A!a').substring(floatingPoint, randomSize));
            $('#password-text').text('Generated password: ' + $('#password').val());
        });

        $('#clear_password').on('click', function(event){
            event.preventDefault();
            $('#password, #password_confirm').val('');
            $('#password-text').text('');
        });

        $('#check_see').on('click', function(event){
            var textbox1 = document.getElementById("password");
            var textbox2 = document.getElementById("password_confirm");

            if($('#check_see').prop('checked'))
            {
                textbox1.setAttribute("type", "text");
                textbox2.setAttribute("type", "text");
            }
            else
            {
                textbox1.setAttribute("type", "password");
                textbox2.setAttribute("type", "password");
            }
        });

        function randomString(length, chars) {
            var mask = '';
            if (chars.indexOf('a') > -1) mask += 'abcdefghijklmnopqrstuvwxyz';
            if (chars.indexOf('A') > -1) mask += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            if (chars.indexOf('#') > -1) mask += '0123456789';
            if (chars.indexOf('!') > -1) mask += '~`!@#$%^&*()_+-={}[]:";\'<>?,./|\\';
            var result = '';
            for (var i = length; i > 0; --i) result += mask[Math.floor(Math.random() * mask.length)];
            return result;
        }

        $('.JQCalendar').datepicker({
            format: 'dd/mm/yyyy'
        });
    </script>

@stop