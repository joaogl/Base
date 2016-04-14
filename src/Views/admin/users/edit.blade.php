@extends('layouts.admin')

{{-- Page title --}}
@section('title')
    Users
    @parent
@stop

{{-- Page content --}}
@section('content')

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Edit user</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">

            @include('errors.list')

            {!! Form::model($user, array('route' => array('users.update', $user->id), 'files' => true)) !!}

            @include('admin.users.partials.form', ['submitButton' => 'Save changes'])

            {!! Form::close() !!}

        </div>

    </div>

@endsection
