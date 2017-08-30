@extends('layouts.app')
@push('css')
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
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
                    <div class="control-group {{ $errors->has('opd') ? 'error' : '' }}">
                        <label for="type" class="control-label">OPD</label>

                        <div class="controls">
                            <?php $selected_data = isset($data)?$data->pegawai->id_unker:old('opd') ?>
                            {{ Form::select('opd', $opd, $selected_data, ['id' => 'opd', 'placeholder' => "Please Select", 'class' => 'span5']) }}

                            @if ($errors->has('opd'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('opd') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="control-group {{ $errors->has('pegawai') ? 'error' : '' }}">
                        <label for="nama" class="control-label">Nama / NIP Pegawai</label>

                        <div class="controls">
                            <?php $selected_pegawai = isset($data)?$data->peg_id:old('pegawai') ?>
                            {{ Form::select('pegawai', $pegawai, $selected_pegawai, ['id' => 'pegawai', 'placeholder' => "Please Select", 'class' => 'span5']) }}

                            @if ($errors->has('pegawai'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('pegawai') }}</strong>
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
<script src="{{ asset('js/select2.min.js') }}"></script>
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

/*  if(Math.round(d) === 0){
    $('#div_jam').show();
  }else{
    $('#div_jam').hide();
  }*/
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
$('#opd').select2({ placeholder: 'Pilih OPD' });
$('#pegawai').select2({
  placeholder: 'Pilih Pegawai',
  allowClear: true,
  ajax: {
    url: "{{ url('api/search_peg?api_token=') }}{{ Auth::user()->api_token }}",
    dataType: 'json',
    delay: 250,
    data: function(params){
      return {
        q: params.term,
        page: params.page,
        per_page: 10,
        opd: $('#opd').val()
      };
    },
    processResults: function(data, params) {
      params.page = params.page || 1;
      return {
        results: data.data,
        pagination: {
          more: (params.page * data.per_page) < data.total
        }
      };
    },
    cache: true
  },
  escapeMarkup: function( markup ){ return markup; },
  minimumInputLength: 1,
  templateResult: formatRepo,
  templateSelection: formatRepoSelection
})

function formatRepo (repo) {
    if (repo.loading) return repo.text;

    var markup = "<div class='select2-result-repository clearfix'>" +
      "<div class='select2-result-repository__meta'>" +
        "<div class='select2-result-repository__title'>" + repo.nama + "</div>";

    if (repo.description) {
      markup += "<div class='select2-result-repository__description'>" + repo.id + "</div>";
    }

    markup += "<div class='select2-result-repository__statistics'>" +
      "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i>NIP: " + repo.nip + "</div>" +
    "</div>" +
    "</div></div>";

    return markup;
}

function formatRepoSelection (repo) {
  return repo.nama || repo.text;
}

});
</script>
@endpush
