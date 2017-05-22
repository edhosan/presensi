@extends('layouts.app')

@push('css')
<link href="{{ asset('easy-autocomplete/dist/easy-autocomplete.min.css') }}" rel="stylesheet">
<link href="{{ asset('easy-autocomplete/dist/easy-autocomplete.themes.min.css') }}" rel="stylesheet">
@endpush

@section('content')

<div class="main">
  <div class="main-inner">
    <div class="container">
      <div class="row">
        <div class="span12">
          {!! Breadcrumbs::render('register') !!}
          <div class="widget">
            <div class="widget-header">
              <i class="icon-user"></i>
              <h3>Manajemen User</h3>
            </div>

            <div class="widget-content">
              <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}" novalidate="novalidate">
                  {{ csrf_field() }}
                  <fieldset>
                    <div class="control-group {{ $errors->has('username') ? 'error' : '' }}">
                        <label for="username" class="control-label">Username / NIP</label>

                        <div class="controls">
                            <input id="username" type="text" class="span4 autocomplete" name="username" value="{{ old('username') }}" autofocus>

                            @if ($errors->has('username'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="control-group {{ $errors->has('password') ? 'error' : '' }}">
                        <label for="password" class="control-label">Password</label>

                        <div class="controls">
                            <input id="password" type="password" class="span4" name="password" required>

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="password-confirm" class="control-label">Confirm Password</label>

                        <div class="controls">
                            <input id="password-confirm" type="password" class="span4" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="control-group {{ $errors->has('name') ? 'error' : '' }}">
                        <label for="name" class="control-label">Nama </label>
                        <div class="controls">
                          <input id="name" type="text" class="span6" name="name" value="{{ old('name') }}" required>
                          @if ($errors->has('name'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('name') }}</strong>
                              </span>
                          @endif
                        </div>
                    </div>

                    <div class="control-group {{ $errors->has('opd') ? 'error' : '' }}">
                        <label for="opd" class="control-label">Organisasi Perangkat Daerah</label>

                        <div class="controls">
                            <input id="opd" type="text" class="span4 autocomplete" name="opd" value="{{ old('opd') }}">
                            <input type="hidden" name="id_unker" value="{{ old('id_unker') }}" id="id_unker">
                            @if ($errors->has('opd'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('opd') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-actions">
                      <button type="submit" class="btn btn-primary">
                          Simpan
                      </button>
                      <a href="{{ route('home') }} " class="btn">Batal</a>
                    </div>
                  </fieldset>

              </form>
            </div>


          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('script')
  <script src="{{ asset('easy-autocomplete/lib/jquery-1.11.2.min.js') }}"></script>
  <script src="{{ asset('easy-autocomplete/dist/jquery.easy-autocomplete.min.js') }}"></script>
  <script type="text/javascript">
  $(function() {
    var optPeg = {
        url: function(phrase) {
          return "{{ url('api/pegawai?api_token=') }}{{ Auth::user()->api_token }}";
        },

        getValue: function(element) {
          return element.nip;
        },

        ajaxSettings: {
          dataType: "json",
          method: "POST",
          data: {
            dataType: "json"
          }
        },

        template: {
      		type: "description",
      		fields: {
      			description: "nama"
      		}
      	},

        list: {
      		match: {
      			enabled: true
      		},
          onSelectItemEvent: function() {
            var value = $("#username").getSelectedItemData().nama;
            $("#name").val(value).trigger("change");
          }
      	},

        preparePostData: function(data) {
          data.phrase = $("#username").val();
          data.unker = "{{ Auth::user()->unker }}";
          return data;
        }
      };

      var optUnker = {
          url: function(phrase) {
            return "{{ url('api/unker?api_token=') }}{{ Auth::user()->api_token }}";
          },

          getValue: function(element) {
            return element.nama_unker;
          },

          ajaxSettings: {
            dataType: "json",
            method: "POST",
            data: {
              dataType: "json"
            }
          },

          template: {
        		type: "description",
        		fields: {
        			description: "id_unker"
        		}
        	},

          list: {
        		match: {
        			enabled: true
        		},
            onSelectItemEvent: function() {
              var value = $("#opd").getSelectedItemData().id_unker;
              $("#id_unker").val(value).trigger("change");
            }
        	},

          preparePostData: function(data) {
            data.phrase = $("#opd").val();
            data.unker = "{{ Auth::user()->unker }}";
            return data;
          }
        };

    $("#username").easyAutocomplete(optPeg);
    $("#opd").easyAutocomplete(optUnker);
  });
  </script>
@endpush
