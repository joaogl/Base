@if ($message = Session::get('success'))
    @push('footer_scripts_stack')
        <script>
            $.notify({
                message: '{{ $message }}',
            },{
                type: "success",
                newest_on_top: true,
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp'
                },
            });
        </script>
    @endpush
@endif

@if ($message = Session::get('error'))
    @push('footer_scripts_stack')
        <script>
            $.notify({
                message: '{{ $message }}',
            },{
                type: "danger",
                newest_on_top: true,
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp'
                },
            });
        </script>
    @endpush
@endif

@if ($message = Session::get('warning'))
    @push('footer_scripts_stack')
        <script>
            $.notify({
                message: '{{ $message }}',
            },{
                type: "warning",
                newest_on_top: true,
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp'
                },
            });
        </script>
    @endpush
@endif

@if ($message = Session::get('info'))
    @push('footer_scripts_stack')
        <script>
            $.notify({
                message: '{{ $message }}',
            },{
                type: "info",
                newest_on_top: true,
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp'
                },
            });
        </script>
    @endpush
@endif