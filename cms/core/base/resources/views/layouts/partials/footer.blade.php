<div class="navbar navbar-sm navbar-footer border-top">
    <div class="container-fluid">
        <span>Copyright &copy; {{ date('Y', time()) }} <a href="{{ route('dashboard.index') }}"><strong>{{
                    config('core.base.common.app_name') }}</strong></a>.</span>
        <span>Developed by <a href="https://code9.vn" title="Code 9 VN" target="_blank"><strong>Code 9</strong></a> with
            ❤️. Version <strong>{{ config('core.base.common.version') }}</strong>.</span>
    </div>
</div>
