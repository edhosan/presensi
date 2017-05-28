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
            {!! Breadcrumbs::render('datainduk_form') !!}
            <div class="widget">
              <div class="widget-header">
                <i class="icon-file"></i>
                <h3>Form Data Induk Pegawai</h3>
              </div>

              <div class="widget-content">
                <form class="form-horizontal" role="form" method="POST" action="{{ isset($data)? route('datainduk_update'):route('datainduk_create') }}" novalidate="novalidate">
                  {{ csrf_field() }}
                  <input type="hidden" name="id" value="{{ $data->id or 0 }}">
                  <div class="span5">
                    <fieldset>
                      <div class="control-group {{ $errors->has('type') ? 'error' : '' }}">
                          <label for="type" class="control-label">Status</label>

                          <div class="controls">
                              <?php $selected_data = isset($data)?$data->type:old('type') ?>
                              {{ Form::select('type', $type, $selected_data) }}

                              @if ($errors->has('type'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('type') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="control-group {{ $errors->has('id_finger') ? 'error' : '' }}">
                          <label for="id_finger" class="control-label">Id Fingerprint</label>

                          <div class="controls">
                              <input id="id_finger" type="text" class="span2" name="id_finger" value="{{ $data->id_finger or old('id_finger') }}">
                              <button type="button" id="btnGenerateId" class="btn btn-warning" text="Loading..."> Buat ID</button>
                              @if ($errors->has('id_finger'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('id_finger') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="control-group {{ $errors->has('nip') ? 'error' : '' }}">
                          <label for="nip" class="control-label">NIP</label>

                          <div class="controls">
                              <input id="nip" type="text" class="span4 autocomplete" name="nip" value="{{ $data->nip or old('nip') }}" autofocus>

                              @if ($errors->has('nip'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('nip') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="control-group {{ $errors->has('nama') ? 'error' : '' }}">
                          <label for="nama" class="control-label">Nama</label>

                          <div class="controls">
                              <input id="nama" type="text" class="span4 autocomplete" name="nama" value="{{ $data->nama or old('nama') }}" autofocus>

                              @if ($errors->has('nama'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('nama') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="control-group {{ $errors->has('gelar_depan') ? 'error' : '' }}">
                          <label for="gelar_depan" class="control-label">Gelar Depan</label>

                          <div class="controls">
                              <input id="gelar_depan" type="text" class="span2" name="gelar_depan" value="{{ $data->gelar_depan or old('gelar_depan') }}">

                              @if ($errors->has('gelar_depan'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('gelar_depan') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="control-group {{ $errors->has('gelar_belakang') ? 'error' : '' }}">
                          <label for="gelar_belakang" class="control-label">Gelar Belakang</label>

                          <div class="controls">
                              <input id="gelar_belakang" type="text" class="span2" name="gelar_belakang" value="{{ $data->gelar_belakang or old('gelar_belakang') }}">

                              @if ($errors->has('gelar_belakang'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('gelar_belakang') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="control-group {{ $errors->has('id_unker') ? 'error' : '' }}">
                          <label for="opd" class="control-label">Organisasi Perangkat Daerah</label>

                          <div class="controls">
                              <input id="nama_unker" type="text" class="span4 autocomplete" name="nama_unker" value="{{$data->nm_unker or old('nama_unker') }}">
                              <input type="hidden" name="id_unker" value="{{$data->id_unker or old('id_unker') }}" id="id_unker">
                              @if ($errors->has('id_unker'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('id_unker') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="control-group {{ $errors->has('subunit') ? 'error' : '' }}">
                          <label for="subunit" class="control-label">Sub Unit</label>

                          <div class="controls">
                              <input id="nama_subunit" type="text" class="span4 autocomplete" name="nama_subunit" value="{{$data->nama_subunit or old('nama_subunit') }}">
                              <input type="hidden" name="subunit" value="{{$data->id_subunit or old('subunit') }}" id="subunit">
                              @if ($errors->has('subunit'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('subunit') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>


                    </fieldset>
                  </div>

                  <div class="span4">
                    <fieldset>
                      <div class="control-group {{ $errors->has('pangkat') ? 'error' : '' }}">
                          <label for="pangkat" class="control-label">Pangkat</label>

                          <div class="controls">
                              <input id="nama_subunit" type="text" class="span4 autocomplete" name="nama_subunit" value="{{$data->nama_subunit or old('nama_subunit') }}">
                              <input type="hidden" name="subunit" value="{{$data->id_subunit or old('subunit') }}" id="subunit">
                              @if ($errors->has('subunit'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('subunit') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>
                    </fieldset>
                  </div>

                  <div class="span11">
                    <div class="form-actions">
                      <button type="submit" class="btn btn-primary">
                          Simpan
                      </button>
                      <a href="{{ route('datainduk_list') }} " class="btn">Batal</a>
                    </div>
                  </div>


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
<script src="{{ asset('js/bootstrap-button.js') }}"></script>
<script src="{{ asset('easy-autocomplete/lib/jquery-1.11.2.min.js') }}"></script>
<script src="{{ asset('easy-autocomplete/dist/jquery.easy-autocomplete.min.js') }}"></script>
<script>
  $('#btnGenerateId').click(function() {
    var btn = $(this)
    btn.button('loading')
    $.ajax({
      url: '{{ url("api/get_idfinger?api_token=") }}{{ Auth::user()->api_token }}',
      type: 'get',
      dataType: "json",
      success: function(response){
        btn.button('reset')
        $('#id_finger').val(response.id_finger)
      },
      error: function(error){
        console.log(error);
      }
    })

  });

  function fillData(param) {
    if(param != undefined){
      $("#nip").val(param.nip).trigger("change");
      $("#nama").val(param.nama).trigger("change");
      $("#nama_unker").val(param.nama_unker).trigger("change");
      $("#id_unker").val(param.id_unker).trigger("change");
      $("#gelar_depan").val(param.gelar_depan).trigger("change");
      $("#gelar_belakang").val(param.gelar_belakang).trigger("change");
      $("#nama_subunit").val(param.nama_subunit).trigger("change");
      $("#subunit").val(param.id_subunit).trigger("change");
    }
  }

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
          var value = $("#nip").getSelectedItemData();
          fillData(value);
        }
      },

      preparePostData: function(data) {
        data.phrase = $("#nip").val();
        @if(Auth::user()->unker != '')
          data.unker =  "{{ Auth::user()->unker }}";
        @else
          data.unker = $('#id_unker').val();
        @endif
        return data;
      }
    };

    var optPegNama = {
        url: function(phrase) {
          return "{{ url('api/pegawai?api_token=') }}{{ Auth::user()->api_token }}";
        },

        getValue: function(element) {
          return element.nama;
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
            description: "nip"
          }
        },

        list: {
          match: {
            enabled: true
          },
          onSelectItemEvent: function() {
            var value = $("#nama").getSelectedItemData();
            fillData(value);
          }
        },

        preparePostData: function(data) {
          data.phrase = $("#nama").val();
          @if(Auth::user()->unker != '')
            data.unker =  "{{ Auth::user()->unker }}";
          @else
            data.unker = $('#id_unker').val();
          @endif
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
              var value = $("#nama_unker").getSelectedItemData().id_unker;
              $("#id_unker").val(value).trigger("change");
            }
          },

          preparePostData: function(data) {
            data.phrase = $("#nama_unker").val();
            data.unker = "{{ Auth::user()->unker }}";
            return data;
          }
        };

        var optSubUnit = {
            url: function(phrase) {
              return "{{ url('api/subunit?api_token=') }}{{ Auth::user()->api_token }}";
            },

            getValue: function(element) {
              return element.nama_subunit;
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
                description: "id_subunit"
              }
            },

            list: {
              match: {
                enabled: true
              },
              onSelectItemEvent: function() {
                var value = $("#nama_subunit").getSelectedItemData();
                $("#subunit").val(value.id_subunit).trigger("change");
              }
            },

            preparePostData: function(data) {
              data.phrase = $("#nama_subunit").val();
              @if(Auth::user()->unker != '')
                data.unker =  "{{ Auth::user()->unker }}";
              @else
                data.unker = $('#id_unker').val();
              @endif

              return data;
            }
          };

    $("#nip").easyAutocomplete(optPeg);
    $("#nama").easyAutocomplete(optPegNama);
    $("#nama_unker").easyAutocomplete(optUnker);
    $("#nama_subunit").easyAutocomplete(optSubUnit);

});
</script>
@endpush
