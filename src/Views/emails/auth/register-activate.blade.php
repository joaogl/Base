@extends('emails/layouts/default')

@section('content')

    <p>Hello {!! $user->first_name !!},</p>

    <p>Welcome to my website! Please click on the following link to confirm your account:</p>

    <p><a href="{!! $activationUrl !!}">{!! $activationUrl !!}</a></p>

    <p>Best regards,</p>

    <p>{{ Base::getSetting('EMAIL_SIGNATURE') }}.</p>

@stop
