@extends('layouts.default')

{{-- Page title --}}
@section('title')
    Settings
    @parent
@stop

{{-- Page content --}}
@section('content')

    <div class="container">

        <h2>List of settings</h2>

        <table class="table table-striped table-hover table-condensed">

            <thead>
            <td>Description</td>
            <td width="30%">Value</td>
            <td width="15%">Date</td>
            <td width="10%">Actions</td>
            </thead>

            <tbody>

            @foreach($movements as $move)

                <tr>
                    <td>{{ $move->name }}</td>
                    <td>{{ $move->value }}</td>
                    <td>{{ Carbon\Carbon::parse($move->modified_at)->format('d M y - H:i') }}</td>
                    <td>
                        <a href="{{ url('settings/' . $move->id . '/edit') }}">Edit</a>
                    </td>
                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

@endsection
