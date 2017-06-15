@extends('layouts.app')
@push('css')
<link href="{{ asset('easy-autocomplete/dist/easy-autocomplete.min.css') }}" rel="stylesheet">
<link href="{{ asset('easy-autocomplete/dist/easy-autocomplete.themes.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@endpush
@section('content')
  <div class="main">
    <div class="main-inner">
      <div class="container">
        <div class="row">
          <div class="span12">
            {!! Breadcrumbs::render('peg_jadwal.form') !!}
            <div class="widget">
              <div class="widget-header">
                <i class="icon-file"></i>
                <h3>Form Jadwal Pegawai</h3>
              </div>

              <div class="widget-content">
                <form class="form-horizontal" role="form" method="POST" action="{{ isset($data)? route('datainduk_update'):route('peg_jadwal.save') }}" novalidate="novalidate">
                  {{ csrf_field() }}
                  <input type="hidden" name="id" value="{{ $data->id or 0 }}">
                  <fieldset>
                    <div class="control-group {{ $errors->has('nama') ? 'error' : '' }}">
                        <label for="nama" class="control-label">Nama</label>

                        <div class="controls">
                            <input id="nama" type="text" class="span5 autocomplete" name="nama" value="{{ $data->nama or old('nama') }}" autofocus>
                            <input type="hidden" name="id_peg" id="id_peg" value="{{ $data->id_peg or old('id_peg') }}">

                            @if ($errors->has('nama'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('nama') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="control-group {{ $errors->has('jadwal') ? 'error' : '' }}">
                        <label for="type" class="control-label">Jadwal</label>

                        <div class="controls">
                            <?php $selected_data = isset($data)?$data->jadwal:old('jadwal') ?>
                            {{ Form::select('jadwal', $jadwal, $selected_data, ['id' => 'jadwal', 'placeholder' => "Please Select"]) }}

                            @if ($errors->has('jadwal'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('jadwal') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-actions">
                      <button type="submit" class="btn btn-primary">Simpan</button>
                      <a href="{{ route('datainduk_list') }} " class="btn">Batal</a>
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
<script src="{{ asset('js/bootstrap-button.js') }}"></script>
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}" ></script>
<script src="{{ asset('js/bootstrap-datepicker.id.min.js') }}" charset="UTF-8"></script>
<script>

$(function() {

  var optPeg = {
      url: function(phrase) {
        return "{{ url('api/getNamePeg?api_token=') }}{{ Auth::user()->api_token }}";
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
        onSelectItemEvent: function() {
          var value = $("#nama").getSelectedItemData();
          $("#id_peg").val(value.id).trigger("change");        
        }
      },

      preparePostData: function(data) {
        data.phrase = $("#nama").val();
        return data;
      }
    };

    $("#nama").easyAutocomplete(optPeg);

});
</script>
@endpush
