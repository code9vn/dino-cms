<!DOCTYPE html>
<html lang="en" dir="ltr" class="layout-static">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('page_title', 'Trang chủ') | {{ config('core.base.common.app_name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('core/base::layouts.partials.styles')
    @stack('styles')
</head>

<body>
    @include('core/base::layouts.partials.header')

    <div class="page-content">
        @include('core/base::layouts.partials.sidebar')
        <div class="content-wrapper">
            <div class="content-inner">
                <div class="content">
                    @yield('content')
                </div>

                @include('core/base::layouts.partials.footer')
            </div>
        </div>
    </div>

    @include('core/base::layouts.partials.notifications')

    @stack('scripts')
</body>

</html>
