@extends('emails/layouts/default')

@section('content')

    <p>Hello {!! $user->first_name !!},</p>

    <p>Welcome to my website! Your account is not active yet, I'm still going to review it and approve it.</p>

    <p>Best regards,</p>

    <p>{{ Base::getSetting('EMAIL_SIGNATURE') }}.</p>

@stop
