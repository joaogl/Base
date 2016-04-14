@extends('emails.layouts.default')

@section('content')

    <p>Hello {!! $user->first_name !!},</p>

    <p>Your account password has been changed by your logged in account.</p>

    <p>If by any chance you did not change your password please report the situation to our staff. <a href="mailto:{{ Base::getSetting('ADMIN_EMAIL') }}">{{ Base::getSetting('ADMIN_EMAIL') }}</a></p>

    <p>Best regards,</p>

    <p>{{ Base::getSetting('EMAIL_SIGNATURE') }}.</p>

@stop
