@extends('layouts.app')

@push('css')
<link href="{{ asset('admin_template/css/pages/signin.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="account-container" id="login">
  <div class="content clearfix">
    <form action="{{ route('login') }}" method="post" role="form" name="frm_login" novalidate="novalidate">
        {{ csrf_field() }}
        <h1>Login</h1>

        <div class="login-fields">
          <p>Silahkan masukan username & password anda</p>

          <div class="control-group{{ $errors->has('username') ? ' error' : '' }}">
            <label for="username">Username</label>
            <input type="text" class="login username-field" placeholder="Username" name="username" value="{{ old('username') }}" required autofocus>

            @if ($errors->has('username'))
                <span class="help-inline">
                    <strong>{{ $errors->first('username') }}</strong>
                </span>
            @endif
          </div>

          <div class="control-group{{ $errors->has('password') ? ' error' : '' }}">
            <label for="Password">Password</label>
            <input type="password" id="password" class="login password-field" placeholder="Password" name="password" required>

            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
          </div>
        </div> <!-- login-fields -->

        <div class="login-actions">
          <span class="login-checkbox">
            <input type="checkbox" name="remember" class="field login-checkbox" {{ old('remember') ? 'checked' : '' }} tabindex="4">
            <label for="remember" class="choice">Ingat selalu</label>
          </span>

          <button type="submit" class="button btn btn-success btn-large">Sign In</button>
        </div><!-- login-actions -->
    </form>
  </div>
</div>
@endsection

@push('script')
<script type="text/javascript">

</script>
@endpush
