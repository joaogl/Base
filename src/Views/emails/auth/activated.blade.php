@extends('emails/layouts/default')

@section('content')

    <p>Hello {!! $user->first_name !!},</p>

    <p>Welcome to my website! Your account is active and ready to be used! </p>

    <p>Thank you for your registration!</p>

    <p>Best regards,</p>

    <p>João Lourenço.</p>

@stop
