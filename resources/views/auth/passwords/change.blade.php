@extends('layouts.app')

@push('css')
@endpush

@section('content')

<div class="main">
  <div class="main-inner">
    <div class="container">
      <div class="row">
        <div class="span12">
          {!! Breadcrumbs::render('change.password') !!}
          <div class="widget">
            <div class="widget-header">
              <i class="icon-user"></i>
              <h3>Ganti Password</h3>
            </div>

            <div class="widget-content">
              <form class="form-horizontal" role="form" method="POST" action="{{ route('password.change') }}" novalidate="novalidate">
                  {{ csrf_field() }}
                  <input type="hidden" name="id" value="{{ $data->id or 0 }}">
                  <fieldset>

                    <div class="control-group {{ $errors->has('password') ? 'error' : '' }}">
                        <label for="password" class="control-label">Password</label>

                        <div class="controls">
                            <input id="password" type="password" class="span4" name="password" value="{{ old('password') }}" required>

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="control-group {{ $errors->has('password_confirmation') ? 'error' : '' }}">
                        <label for="password-confirm" class="control-label">Konfirmasi Password</label>

                        <div class="controls">
                            <input id="password-confirm" type="password" class="span4" name="password_confirmation" value="{{ old('password_confirmation') }}" required>

                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
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
            var value = $("#username").getSelectedItemData();
            $("#name").val(value.nama).trigger("change");
            $("#opd").val(value.nama_unker).trigger("change");
            $("#id_unker").val(value.id_unker).trigger("change");
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
