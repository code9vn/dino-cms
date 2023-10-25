<script src="{{ asset('cms/js/vendor/notifications/noty.min.js') }}"></script>
<script src="{{ asset('cms/js/vendor/notifications/sweet_alert.min.js') }}"></script>
<script src="{{ asset('cms/js/script.js') }}"></script>
<script type="text/javascript">
    var inProcess = false;

    @if (isset($errors) && count($errors->all()) > 0)
        @foreach ($errors->all() as $message)
            showNotify("{{ $message }}", "error");
        @endforeach
    @endif

    @if (session('flash_data'))
        @php
            $flash_data = session('flash_data');
        @endphp
        showNotify("{{ $flash_data['message'] }}", "{{ $flash_data['type'] }}");
    @endif
</script>
