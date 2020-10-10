<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{%brand_name%} Material Design Bootstrap</title>
    <link href="{{ asset('{%brand_string%}/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('{%brand_string%}/css/all.css') }}" rel="stylesheet">
    <style type="text/css">
    @media (min-width: 800px) and (max-width: 850px) {
      .navbar:not(.top-nav-collapse) {
        background: #1C2331 !important;
      }
    }
    </style>
    @stack('styles')
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark scrolling-navbar">
      <div class="container">
        <!-- Brand -->
        <a class="navbar-brand" href="{{ slug_route('{%brand_string%}.home','') }}">
          <strong>{%brand_name%}</strong>
        </a>
        <!-- Collapse -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Links -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Left -->
          <ul class="navbar-nav mr-auto">
            <x-{%brand_string%}::navbar-top groupSlug="sample-navbar" />
          </ul>
          <!-- Right -->
          <ul class="navbar-nav nav-flex-icons">
            <x-{%brand_string%}::navbar-top-login />
          </ul>
        </div>
      </div>
    </nav>
    <!-- Navbar -->
    <!--Carousel Wrapper-->
    <x-{%brand_string%}::carousel slug="sample-carousel" :tags="['new','hot']" />
    <!--/.Carousel Wrapper-->
    <!--Main layout-->
    <main>
      <div class="container">
        @yield('content')
      </div>
    </main>
    <!--Main layout-->
    <!--Footer-->
    <footer class="page-footer text-center font-small mt-4 wow fadeIn">
      <hr class="my-4">
      <!-- Social icons -->
      <div class="pb-4">
        <a href="https://www.facebook.com/mdbootstrap" target="_blank">
          <i class="fab fa-facebook-f mr-3"></i>
        </a>
        <a href="https://twitter.com/MDBootstrap" target="_blank">
          <i class="fab fa-twitter mr-3"></i>
        </a>
        <a href="https://www.youtube.com/watch?v=7MUISDJ5ZZ4" target="_blank">
          <i class="fab fa-youtube mr-3"></i>
        </a>
        <a href="https://plus.google.com/u/0/b/107863090883699620484" target="_blank">
          <i class="fab fa-google-plus-g mr-3"></i>
        </a>
        <a href="https://dribbble.com/mdbootstrap" target="_blank">
          <i class="fab fa-dribbble mr-3"></i>
        </a>
        <a href="https://pinterest.com/mdbootstrap" target="_blank">
          <i class="fab fa-pinterest mr-3"></i>
        </a>
        <a href="https://github.com/mdbootstrap/bootstrap-material-design" target="_blank">
          <i class="fab fa-github mr-3"></i>
        </a>
        <a href="http://codepen.io/mdbootstrap/" target="_blank">
          <i class="fab fa-codepen mr-3"></i>
        </a>
      </div>
      <!-- Social icons -->
      <!--Copyright-->
      <div class="footer-copyright py-3">
        © {{ date('Y') }} Copyright: {%brand_name%}
      </div>
      <!--/.Copyright-->
    </footer>

    <x-{%brand_string%}::login-modal />

    <script src="{{ asset('{%brand_string%}/js/app.js') }}"></script>
    <script src="{{ asset('{%brand_string%}/js/all.js') }}"></script>
    <script type="text/javascript">
    new WOW().init();
    </script>
    @stack('scripts')
  </body>
</html>
