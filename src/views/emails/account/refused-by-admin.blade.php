@extends('emails.layouts.default')

@section('content')

    <p>Hello {!! $user->first_name !!},</p>

    <p>Our administrators refused your account registration attempt. </p>

    <p>Please read the regulations and be sure that you are following them.</p>

    <p>If by and reason you think your account registration was refused with no valid reason contact us on <a href="mailto:{{ Base::getSetting('ADMIN_EMAIL') }}">{{ Base::getSetting('ADMIN_EMAIL') }}</a></p>

    <p>Best regards,</p>

    <p>{{ Base::getSetting('EMAIL_SIGNATURE') }}.</p>

@endsection
