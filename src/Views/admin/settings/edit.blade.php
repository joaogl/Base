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

        {!! Form::model($setting, array('route' => array('settings.update', $setting->id))) !!}

        @include('admin.settings.partials.form', ['submitButton' => 'Update setting'])

        {!! Form::close() !!}

    </div>

@endsection
