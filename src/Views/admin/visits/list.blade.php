@extends('layouts.admin')

{{-- Page title --}}
@section('title')
    Visits
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/extensions/bootstrap/dataTables.bootstrap.css') }}" />
    <link href="{{ asset('css/tables.css') }}" rel="stylesheet" type="text/css" />
@stop

{{-- Page content --}}
@section('content')

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    List of the recent visits
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
                    <th>IP</th>
                    <th>URL</th>
                    <th>Agent</th>
                    <th>Visited at</th>
                    <th>Country</th>
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
                "ajax": '../admin/getVisits',
                "order": [[ 3, "desc" ]],
                "columns": [
                    { "data": "ip" },
                    { "data": "url" },
                    { "data": "browser" },
                    { "data": "created_at" },
                    { "data": "country" },
                ]
            });

            setInterval( function () {
                table.ajax.reload();
            }, 10000 );
        });

    </script>

@stop
