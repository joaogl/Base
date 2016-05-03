@extends('emails.layouts.default')

@section('content')

    <p>Hello {!! $user->first_name !!},</p>

    <p>An administrator created a new account for you. Your login information is the following: </p>

    <ul>
        <li><strong>Url: </strong> <a href="{{ route('login') }}">{{ route('login') }}</a></li>
        <li><strong>Username: </strong> {{ $user->username }}</li>
        <li><strong>Password: </strong> {{ $new_password }}</li>
    </ul>

    <p>Be aware that your account is not active yet, you will receive an activation email soon.</p>

    <p>Best regards,</p>

    <p>{{ Base::getSetting('EMAIL_SIGNATURE') }}.</p>

@endsection
