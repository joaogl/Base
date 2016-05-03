@extends('layouts.admin')

{{-- Page title --}}
@section('title')
    Queues
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
                    List of the recent jobs in queue
                    <small>This page gets updated every 10 seconds</small>
                </h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <table class="table table-bordered" id="table">
                <thead>
                    <tr class="filters">
                        <th>Queue</th>
                        <th width="25%">Job</th>
                        <th width="25%">Payload</th>
                        <th>Attempts</th>
                        <th>Running</th>
                        <th>Running since</th>
                        <th>Created at</th>
                    </tr>
                </thead>
            </table>

        </div>

    </div>

@endsection

@section('footer_scripts')
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/datatables/extensions/bootstrap/dataTables.bootstrap.js') }}"></script>

    <script>
        $(document).ready(function() {
            var table = $('#table').DataTable({
                "ajax": '../admin/getQueues',
                "order": [[ 4, "desc" ]],
                "columns": [
                    { "data": "queue" },
                    { "data": "payload" },
                    { "data": "data" },
                    { "data": "attempts" },
                    { "data": "reserved" },
                    { "data": "reserved_at" },
                    { "data": "created_at" }
                ]
            });

            setInterval( function () {
                table.ajax.reload();
            }, 10000 );
        });

    </script>

@endsection
