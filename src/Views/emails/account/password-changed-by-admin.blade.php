@extends('emails.layouts.default')

@section('content')

    <p>Hello {!! $user->first_name !!},</p>

    <p>An administrator changed your password. Your new login information is the following: </p>

    <ul>
        <li><strong>Username: </strong> {{ $user->username }}</li>
        <li><strong>Password: </strong> {{ $new_password }}</li>
    </ul>

    <p>Best regards,</p>

    <p>{{ Base::getSetting('EMAIL_SIGNATURE') }}.</p>

@stop
