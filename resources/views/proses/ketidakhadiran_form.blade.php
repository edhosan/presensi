@extends('layouts.app')
@push('css')
<link href="{{ asset('easy-autocomplete/dist/easy-autocomplete.min.css') }}" rel="stylesheet">
<link href="{{ asset('easy-autocomplete/dist/easy-autocomplete.themes.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<link href="{{ asset('bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}" rel="stylesheet">
@endpush
@section('content')
  <div class="main">
    <div class="main-inner">
      <div class="container">
        <div class="row">
          <div class="span12">
            {!! Breadcrumbs::render('ketidakhadiran.form') !!}
            <div class="widget">
              <div class="widget-header">
                <i class="icon-file"></i>
                <h3>Form Ketidakhadiran Pegawai</h3>
              </div>

              <div class="widget-content">
                <form class="form-horizontal" role="form" method="POST" action="{{ isset($data)? route('ketidakhadiran.update'):route('ketidakhadiran.save') }}" novalidate="novalidate" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <input type="hidden" name="id" value="{{ $data->id or 0 }}">
                  <fieldset>
                    <div class="control-group {{ $errors->has('nama') ? 'error' : '' }}">
                        <label for="nama" class="control-label">Nama / NIP Pegawai</label>

                        <div class="controls">
                            <input id="nama" type="text" class="span5 autocomplete" name="nama" value="{{ $data->pegawai->nama or old('nama') }}" autofocus>
                            <input type="hidden" name="id_peg" id="id_peg" value="{{ $data->peg_id or old('id_peg') }}">

                            @if ($errors->has('nama'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('nama') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="control-group {{ $errors->has('ijin') ? 'error' : '' }}">
                        <label for="type" class="control-label">Keterangan Tidak Hadir</label>

                        <div class="controls">
                            <?php $selected_data = isset($data)?$data->keterangan_id:old('ijin') ?>
                            {{ Form::select('ijin', $ijin, $selected_data, ['id' => 'ijin', 'placeholder' => "Please Select"]) }}

                            @if ($errors->has('ijin'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('ijin') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="control-group {{ $errors->has('start') ? 'error' : '' }} {{ $errors->has('end') ? 'error' : '' }}">
                        <label for="start" class="control-label">Tanggal</label>
                        <div class="controls controls-row">
                          @php
                            $start = old('start');
                            if(!empty($data))
                              $start = Carbon\Carbon::parse($data['start'])->format('d-m-Y');
                          @endphp
                          <input id="start" type="text" class="span2" name="start" value="{{ $start }}">
                          &nbsp;s/d&nbsp;
                          @php
                            $end = old('end');
                            if(!empty($data))
                              $end = Carbon\Carbon::parse($data['end'])->format('d-m-Y');
                          @endphp
                          <input id="end" type="text" class="span2" name="end" value="{{ $end }}">

                          @if ($errors->has('start'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('start') }}</strong>
                              </span>
                          @endif

                          @if ($errors->has('end'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('end') }}</strong>
                              </span>
                          @endif
                        </div>
                    </div>

                    <div class="control-group {{ $errors->has('jam_start') ? 'error' : '' }}" id="div_jam">
                        <label for="jam_start" class="control-label">Jam</label>

                        <div class="controls controls-row">
                            <div class="input-append bootstrap-timepicker timepicker">
                                <input id="jam_start" name="jam_start" type="text" class="span1 m-wrap" value="{{ $data->jam_start or old('jam_start') }}">
                                <button type="button" class="btn"><i class="icon-time "></i></button>
                            </div>
                            &nbsp;s/d&nbsp;
                            <div class="input-append bootstrap-timepicker timepicker">
                                <input id="jam_end" name="jam_end" type="text" class="span1 m-wrap" value="{{ $data->jam_end or old('jam_end') }}">
                                <button type="button" class="btn"><i class="icon-time "></i></button>
                            </div>

                            @if ($errors->has('jam_start'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('jam_start') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="control-group {{ $errors->has('keperluan') ? 'error' : '' }}">
                        <label for="keperluan" class="control-label">Alasan / Keperluan</label>

                        <div class="controls">
                            <textarea rows="3" class="span4" name="keperluan">{{ $data->keperluan or old('keperluan') }}</textarea>

                            @if ($errors->has('keperluan'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('keperluan') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="control-group {{ $errors->has('file') ? 'error' : '' }}">
                      <label for="file" class="control-label">Upload File</label>

                      <div class="controls">
                          {!! Form::file('file', null) !!}

                          @if ($errors->has('file'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('file') }}</strong>
                              </span>
                          @endif
                      </div>
                    </div>

                    @if(isset($data->filename))
                    <div class="control-group">
                      <label for="" class="control-label"></label>
                      <div class="controls">
                        <iframe src="{{ URL::to('/') }}/catalog/surat/{{ $data->filename }}" width="30%" height="5%"></iframe>
                        <a href="{{ URL::to('/') }}/catalog/surat/{{ $data->filename }}">&nbsp;Download File</a>
                      </div>
                    </div>
                    @endif


                    <div class="form-actions">
                      <button type="submit" class="btn btn-primary">Simpan</button>
                      <a href="{{ route('ketidakhadiran.list') }} " class="btn">Batal</a>
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
<script src="{{ asset('bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script>
<script>

$(function() {

$.changeDate = function () {
  var dateStart = $('#start').datepicker('getDate');
  var dateEnd = $('#end').datepicker('getDate');
  var d = (dateEnd - dateStart) / (1000 * 60 * 60 * 24);

  if(Math.round(d) === 0){
    $('#div_jam').show();
  }else{
    $('#div_jam').hide();
  }
};

var formatCalendar = {
  format: 'dd-mm-yyyy',
  language: 'id',
  autoclose : true,
  todayHighlight : true
};

$('#start').datepicker( formatCalendar );
$('#end').datepicker( formatCalendar );

$('#start').change( $.changeDate );
$('#end').change( $.changeDate );

$('#jam_start').timepicker({ showMeridian: false });
$('#jam_end').timepicker({ showMeridian: false });

$('#div_jam').hide();

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
