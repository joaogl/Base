@extends('emails.layouts.default')

@section('content')

    <p>Hello {!! $user->first_name !!},</p>

    <p>Our administrators accepted your account registration. </p>

    <p>You can now login using your credentials.</p>

    <p>Best regards,</p>

    <p>{{ Base::getSetting('EMAIL_SIGNATURE') }}.</p>

@endsection
