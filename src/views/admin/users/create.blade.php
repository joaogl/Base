@extends('layouts.admin')

{{-- Page title --}}
@section('title')
    Users
    @parent
@endsection

{{-- Page content --}}
@section('content')

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Create user</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">

            {!! Form::model(null, array('route' => array('create.user'), 'files' => true)) !!}

            @include('admin.users.partials.form', ['submitButton' => 'Create user'])

            {!! Form::close() !!}

        </div>

    </div>

@endsection
