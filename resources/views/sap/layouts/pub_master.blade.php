<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">{{ config('app.name') }}</div>
            </a>
            {{-- <x-sap-menu></x-sap-menu> --}}
            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <!-- Topbar Search -->
                    {{-- <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form> --}}
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <x-sap-dropdown-menu/>
                    </ul>
                </nav>
                <div class="container-fluid">
                    @yield('container')
                    <div id="overlayLoader">
                      <div class="w-100 d-flex justify-content-center align-items-center">
                        <div class="spinner"></div>
                      </div>
                    </div>
                </div>
            </div>
            <footer class="sticky-footer">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; {{ config('app.name') }} {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    @routes
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/all.js') }}"></script>
    <script src="{{ asset('js/datatableformhandling.min.js') }}"></script>
    <script>
    $(function() {
        @if (env('PUSHER_APP_KEY') != '')
        Pusher.logToConsole = true;
        let pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
          cluster: 'ap1',
          forceTLS: true
        });
        let pusher_callback = function(data) {
            let icon = '{{ asset('sap/logo.png') }}';
            if (_.isUndefined(data.icon) === false) {
                icon = data.icon;
            }
            let link = '';
            if (_.isUndefined(data.link) === false) {
                link = data.link;
            }
            let timeout = 5000;
            if (_.isUndefined(data.timeout) === false) {
                timeout = data.timeout;
            }
            let title = '{{ env('APP_NAME') }} Web Notification';
            if (_.isUndefined(data.title) === false) {
                title = data.title;
            }
            let message = '';
            if (_.isUndefined(data.message) === false) {
                message = data.message;
            } else if (_.isArray(data)) {
                message = data.join("\n");
            } else if (_.isString(data)){
                message = data;
            }
            Push.create(title, {
                body: message,
                icon: icon,
                link: link,
                timeout: timeout,
                onClick: function () {
                    window.focus();
                    this.close();
                }
            });
        }
        let channel = pusher.subscribe('{{ sha1(env('APP_NAME')) }}');
        channel.bind('{{ sha1('general') }}', pusher_callback);
        @endif
    });
    </script>
    @stack('scripts')
</body>

</html>