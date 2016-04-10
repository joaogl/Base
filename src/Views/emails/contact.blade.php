@extends('emails/layouts/default')

@section('content')
    <p>Hello {{ $data['contact-name'] }},</p>

    <p>Welcome to @lang('general.site_name')! We have received your details.</p>

    <p>The provided details are:</p>

    <p>  {{ $data['contact-msg'] }}  </p>

    <p> Thank you for Contacting @lang('general.site_name')! We will revert you shortly.</p>

    <p>Best regards,</p>

    <p>@lang('general.site_name') Team</p>
@stop
