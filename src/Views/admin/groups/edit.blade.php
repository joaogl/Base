@extends('layouts.admin')

{{-- Page title --}}
@section('title')
    Groups
    @parent
@endsection

{{-- Page content --}}
@section('content')

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Edit group</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">

            @include('errors.list')

            {!! Form::model($group, array('route' => array('group.update', $group->id))) !!}

            @include('admin.groups.partials.form', ['submitButton' => 'Update groups'])

            {!! Form::close() !!}

        </div>

    </div>

@endsection
