@extends('layouts.admin')

{{-- Page title --}}
@section('title')
    Settings
    @parent
@stop

{{-- Page content --}}
@section('content')

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">List of settings</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">

            <table class="table table-striped table-hover table-condensed">

                <thead>
                <td>Description</td>
                <td width="30%">Value</td>
                <td width="15%">Date</td>
                <td width="10%">Actions</td>
                </thead>

                <tbody>

                @foreach($settings as $move)

                    <tr>
                        <td>{{ $move->name }}</td>
                        <td>
                            @if (sizeof($move->options()) > 0)
                                {{ $move->options()[$move->value] }}
                            @else
                                {{ $move->value }}
                            @endif
                        </td>
                        <td>{{ Carbon\Carbon::parse($move->modified_at)->format('d M y - H:i') }}</td>
                        <td>
                            <a href="{{ url('admin/settings/' . $move->id . '/edit') }}">Edit</a>
                        </td>
                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

    </div>

@endsection
