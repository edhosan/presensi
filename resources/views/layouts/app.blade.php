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
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
    <link href="{{ asset('admin_template/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('toastr/toastr.min.css') }}" rel="stylesheet">
    @stack('css')
</head>
<body>
  <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </a>
            <a class="brand" href="#">
              SIM - PRESENSI
            </a>

            @if (Auth::check())
              <div class="nav-collapse">
                <ul class="nav pull-right">
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                      class="icon-user"></i> User: {{ Auth::user()->name }} <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                      <li><a href="javascript:;">Ganti Password</a></li>
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
        toastr.success('{{ session("success") }}','Success',{timeout: 5000});
      @endif

      @if(session('error'))
        toastr.error('{{ session("error") }}','Error',{timeout: 5000});
      @endif
    </script>
    @stack('script')
</body>
</html>
