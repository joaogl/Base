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
                <h1 class="page-header">Edit user</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">

            @include('errors.list')

            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#userdata" data-toggle="tab" aria-expanded="true">User information</a>
                </li>
                <li class="">
                    <a href="#usergroups" data-toggle="tab" aria-expanded="false">User groups</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane fade active in" id="userdata">
                    <div class="panel-body">

                        {!! Form::model($user, array('route' => array('users.update', $user->id), 'files' => true)) !!}

                        @include('admin.users.partials.form', ['submitButton' => 'Save changes'])

                        {!! Form::close() !!}

                    </div>
                </div>
                <div class="tab-pane fade" id="usergroups">
                    <div class="panel-body">

                        {!! Form::model(null, array('route' => array('users.add.group', $user->id))) !!}

                            <div class="row">
                                <div class="col-md-8">

                                    <table class="table table-bordered" id="table3">
                                        <thead>
                                            <tr class="filters">
                                                <th>Group name</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($user->roles as $role)
                                                <tr>
                                                    <td>{!! $role->name !!}</td>
                                                    <td>
                                                        <a href="{{ route('confirm-remove/group', ['userId' => $user->id, 'groupId' => $user->id]) }}" data-toggle="modal" data-target="#delete_confirm"><i class="fa fa-trash" title="Remove group"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>

                                </div>

                                @if (sizeof($groups) > 0)

                                    <div class="col-md-4">

                                        {!! Form::label('group', 'Add group') !!}
                                        {!! Form::select('group', $groups, null, ['class' => 'form-control']) !!}

                                        <br>

                                        {!! Form::submit('Add group', ['class' => 'btn btn-primary form-control']) !!}

                                    </div>

                                @endif

                            </div>

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('footer_scripts')

    <div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content"></div>
        </div>
    </div>

    <script>
        $(function () {
            $('body').on('hidden.bs.modal', '.modal', function () {
                $(this).removeData('bs.modal');
            });
        });
    </script>

@endsection
