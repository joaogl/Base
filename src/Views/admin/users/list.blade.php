@extends('layouts.admin')

{{-- Page title --}}
@section('title')
        @if($pending)
            Pending users
        @else
            Users
        @endif
    @parent
@endsection

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/extensions/bootstrap/dataTables.bootstrap.css') }}" />
    <link href="{{ asset('css/tables.css') }}" rel="stylesheet" type="text/css" />
@endsection

{{-- Page content --}}
@section('content')

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    @if($pending)
                        List of pending users
                    @else
                        List of users

                        <div style="float: right;">
                            <a href="{{ route('create.user') }}">
                                {!! Form::submit('User new user', ['class' => 'btn btn-primary form-control']) !!}
                            </a>
                        </div>
                    @endif
                </h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <table class="table table-bordered" id="table">
                <thead>
                <tr class="filters">
                    <th>First name</th>
                    <th>Last name</th>
                    <th>User e-mail</th>
                    @if(!$pending)
                        <th>Status</th>
                        <th>Last login</th>
                    @endif
                    <th>Created at</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($users as $user)
                    <tr>
                        <td>{!! $user->first_name !!}</td>
                        <td>{!! $user->last_name !!}</td>
                        <td>{!! $user->email !!}</td>
                        @if(!$pending)
                            <td>
                                {{ $possibleStatus[$user->status] }}
                            </td>
                            <td>{!! $user->last_login != null ? $user->last_login->diffForHumans() : 'Never logged in' !!}</td>
                        @endif
                        <td>{!! $user->created_at->diffForHumans() !!}</td>
                        <td>

                            @if($pending)
                                <a href="{{ route('users.accept', $user->id) }}"><i style="color: green" class="fa fa-check" title="Accept user"></i></a>

                                <a href="{{ route('users.refuse', $user->id) }}"><i style="color: red" class="fa fa-times" title="Refuse user"></i></a>

                                 |
                            @endif

                            <a href="{{ route('users.show', $user->id) }}"><i class="fa fa-eye" title="View  user"></i></a>

                            <a href="{{ route('users.update', $user->id) }}"><i class="fa fa-pencil" title="Edit user"></i></a>

                            @if ((Sentinel::check()->id != $user->id))
                                <a href="{{ route('confirm-delete/user', $user->id) }}" data-toggle="modal" data-target="#delete_confirm"><i class="fa fa-trash" title="Delete user"></i></a>
                            @endif
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>

        </div>

    </div>

@endsection

@section('footer_scripts')
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/extensions/bootstrap/dataTables.bootstrap.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                @if($pending)
                    "order": [[ 0, "asc" ], [ 1, "asc" ]],
                @else
                    "order": [[ 3, "asc" ], [ 0, "asc" ], [ 1, "asc" ]],
                @endif
            });
        });
    </script>

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
