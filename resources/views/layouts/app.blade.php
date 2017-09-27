<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('admin_template/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin_template/css/bootstrap-responsive.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin_template/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('fonts/vendor/open-sans/stylesheet.css') }}" rel="stylesheet">
    <link href="{{ asset('admin_template/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('toastr/toastr.min.css') }}" rel="stylesheet">
    @stack('css')
</head>
<body>
  <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="#">
              <img src="{{ asset('images/logo.png') }}" alt="" height="50" width="50">
                <em>Attendance Information System</em>
              </img>
            </a>


            @if (Auth::check())
              <div class="nav-collapse">
                <ul class="nav pull-right">
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                      class="icon-user"></i> User: {{ Auth::user()->name }} <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                      <li><a href="{{ route('change.password') }}">Ganti Password</a></li>
                      <li><a href="{{ route('logout') }}">Logout</a></li>
                    </ul>
                  </li>
                </ul>
              </div>
            @endif
        </div>
      </div>
  </div>

  @yield('content')

  @if(Auth::check())
    @include('partial.footer')
  @endif

    <!-- Scripts -->
    <script src="{{ asset('admin_template/js/jquery-1.7.2.min.js') }}"></script>
    <script src="{{ asset('admin_template/js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('toastr/toastr.min.js') }}"></script>
    <script type="text/javascript">
      @if(session('success'))
        toastr.success('{{ session("success") }}','Success');
      @endif

      @if(session('error'))
        toastr.error('{{ session("error") }}','Error');
      @endif
    </script>
    @stack('script')
</body>
</html>
