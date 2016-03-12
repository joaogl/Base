@extends('layouts.default')

{{-- Page title --}}
@section('title')
    Settings
    @parent
@stop

{{-- Page content --}}
@section('content')

    <div class="container">
        <h2>Edit setting</h2>

        <hr/>

        @include('errors.list')

        {!! Form::model($movement, array('route' => array('settings.update', $movement->id))) !!}

        @include('admin.settings.partials.form', ['submitButton' => 'Update setting'])

        {!! Form::close() !!}

    </div>

@endsection
