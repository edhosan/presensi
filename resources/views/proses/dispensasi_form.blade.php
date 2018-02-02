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
            {!! Breadcrumbs::render('dispensasi.form') !!}
            <div class="widget">
              <div class="widget-header">
                <i class="icon-file"></i>
                <h3>Form Dispensasi Pegawai</h3>
              </div>

              <div class="widget-content">
                <form class="form-horizontal" role="form" method="POST" action="{{ isset($data)? route('dispensasi.update'):route('dispensasi.save') }}" novalidate="novalidate" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <input type="hidden" name="id" value="{{ $data->id or 0 }}">
                  <fieldset>
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

                    <div class="control-group {{ $errors->has('tanggal') ? 'error' : '' }} {{ $errors->has('end') ? 'error' : '' }}">
                        <label for="start" class="control-label">Tanggal</label>
                        <div class="controls controls-row">
                          @php
                            $tanggal = old('tanggal');
                            if(!empty($data))
                              $tanggal = Carbon\Carbon::parse($data['tanggal'])->format('d-m-Y');
                          @endphp
                          <input id="tanggal" type="text" class="span2" name="tanggal" value="{{ $tanggal }}">

                          @if ($errors->has('tanggal'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('tanggal') }}</strong>
                              </span>
                          @endif
                        </div>
                    </div>

                    <div class="control-group {{ $errors->has('koreksi_jam') ? 'error' : '' }}">
                        <label for="koreksi_jam" class="control-label">Koreksi Jam Masuk / Pulang</label>

                        <div class="controls controls-row">
                            <div class="input-append bootstrap-timepicker timepicker">
                                <input id="koreksi_jam" name="koreksi_jam" type="text" class="span1 m-wrap" value="{{ $data->koreksi_jam or old('koreksi_jam') }}">
                                <button type="button" class="btn"><i class="icon-time "></i></button>
                            </div>

                            @if ($errors->has('koreksi_jam'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('koreksi_jam') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="control-group {{ $errors->has('alasan') ? 'error' : '' }}">
                        <label for="keperluan" class="control-label">Alasan / Keperluan</label>

                        <div class="controls">
                            <textarea rows="3" class="span4" name="alasan">{{ $data->alasan or old('alasan') }}</textarea>

                            @if ($errors->has('alasan'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('alasan') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="control-group {{ $errors->has('file') ? 'error' : '' }}">
                      <label for="file" class="control-label">Upload Surat Ijin</label>

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
                        <iframe src="{{ URL::to('/') }}/public/catalog/surat/dispensasi/{{ $data->filename }}" width="30%" height="5%"></iframe>
                        <a href="{{ URL::to('/') }}/public/catalog/surat/dispensasi/{{ $data->filename }}">&nbsp;Download File</a>
                      </div>
                    </div>
                    @endif


                    <div class="form-actions">
                      <button type="submit" class="btn btn-primary">Simpan</button>
                      <a href="{{ route('dispensasi.list') }} " class="btn">Batal</a>
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

var formatCalendar = {
  format: 'dd-mm-yyyy',
  language: 'id',
  autoclose : true,
  todayHighlight : true
};

$('#tanggal').datepicker( formatCalendar );

$('#koreksi_jam').timepicker({
  showMeridian: false,
  icons: {
    up: 'icon icon-caret-up',
    down: 'icon icon-caret-down'
  }
});

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
        per_page: 10
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
