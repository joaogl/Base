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

            @foreach($settings as $setting)

                <tr>
                    <td>{{ $setting->name }}</td>
                    <td>{{ $setting->value }}</td>
                    <td>{{ Carbon\Carbon::parse($setting->modified_at)->format('d M y - H:i') }}</td>
                    <td>
                        <a href="{{ url('settings/' . $setting->id . '/edit') }}">Edit</a>
                    </td>
                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

@endsection
