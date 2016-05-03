@extends('layouts.admin')

{{-- Page title --}}
@section('title')
    Groups
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
                    List of groups

                    <div style="float: right;">
                        <a href="{{ route('create.group') }}">
                            {!! Form::submit('Create new group', ['class' => 'btn btn-primary form-control']) !!}
                        </a>
                    </div>

                </h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <table class="table table-bordered" id="table">
                <thead>
                <tr class="filters">
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($groups as $group)
                    <tr>
                        <td>{!! $group->id !!}</td>
                        <td>{!! $group->name !!}</td>
                        <td>
                            <a href="{{ route('group.show', $group->id) }}"><i class="fa fa-eye" title="View group"></i></a>

                            <a href="{{ route('group.update', $group->id) }}"><i class="fa fa-pencil" title="Edit group"></i></a>

                            <a href="{{ route('confirm-delete/group', $group->id) }}" data-toggle="modal" data-target="#delete_confirm"><i class="fa fa-trash" title="Delete group"></i></a>
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
            $('#table').DataTable();
        });
    </script>

    <div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="delete_confirm_title" aria-hidden="true">
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
