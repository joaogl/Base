@extends('emails/layouts/default')

@section('content')

    <p>Hello {!! $user->first_name !!},</p>

    <p>Your account password has been successfully changed.</p>

    <p>Best regards,</p>

    <p>João Lourenço.</p>

@stop
