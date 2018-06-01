@php
  $bulan = [
    '1' =>  'Januari',
    '2' =>  'Februari',
    '3' =>  'Maret',
    '4' =>  'April',
    '5' =>  'Mei',
    '6' =>  'Juni',
    '7' =>  'Juli',
    '8' =>  'Agustus',
    '9' =>  'September',
    '10'  =>  'Oktober',
    '11'  =>  'November',
    '12'  =>  'Desember'
  ];

  $year = [];
  $t_year = Carbon\Carbon::now();
  $i_year = date("Y");
  for($i=0;$i<=4;$i++){
    $i_year= $i_year - 1;
    $year[$i_year+1] = $i_year+1;
  }
@endphp

@extends('layouts.app')
@push('css')
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@endpush
@section('content')
@if(Auth::check())
   @include('partial.subnavbar')
@endif
<div class="main">
  <div class="main-inner">
      <div class="container">
          {!! Breadcrumbs::render('laporan.bulanan') !!}
          <div class="row">
            <div class="span12">
              <div class="widget">
                <div class="widget-header">
                  <i class="icon-file"></i>
                  <h3>Laporan Kehadiran Bulanan</h3>
                </div>

                <div class="widget-content">
                  <form target="_blank" class="form-horizontal" role="form" method="POST" id="form" action="{{ route('laporan.bulanan.report') }}" novalidate="novalidate">
                    {{ csrf_field() }}
                    <fieldset>
                      @role(['super-admin'])
                      <div class="control-group {{ $errors->has('opd') ? 'error' : '' }}">
                          <label for="type" class="control-label">OPD</label>

                          <div class="controls">
                              <?php $selected_data = isset($data)?$data->opd:old('opd') ?>
                              {{ Form::select('opd', $opd, $selected_data, ['id' => 'opd', 'placeholder' => "Please Select", 'class' => 'span5']) }}

                              @if ($errors->has('opd'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('opd') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>
                      @endrole

                      <div class="control-group {{ $errors->has('bulan') ? 'error' : '' }} {{ $errors->has('tahun') ? 'error' : '' }}">
                          <label for="start" class="control-label">Bulan</label>
                          <div class="controls controls-row">
                            {{ Form::select('bulan', $bulan, old('bulan'), ['id' => 'bulan', 'placeholder' => "Please Select", 'class' => 'span2']) }}
                            {{ Form::select('tahun', $year, old('tahun'), ['id' => 'tahun', 'placeholder' => "Please Select"]) }}

                            @if ($errors->has('bulan'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('bulan') }}</strong>
                                </span>
                            @endif

                            @if ($errors->has('tahun'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('tahun') }}</strong>
                                </span>
                            @endif
                          </div>
                      </div>

                      <div class="control-group {{ $errors->has('peg') ? 'error' : '' }}">
                          <label for="nama" class="control-label">Mengetahui Pejabat Berwenang</label>

                          <div class="controls">
                            <select name="peg" id="peg" class="span5"></select>

                              @if ($errors->has('peg'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('peg') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Cetak Laporan</button>
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
<pre>{{Auth::user()->unker}}</pre>
@endsection

@push('script')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}" ></script>
<script src="{{ asset('js/bootstrap-datepicker.id.min.js') }}" charset="UTF-8"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<script>
$(function() {

  var formatCalendar = {
    format: 'dd-mm-yyyy',
    language: 'id',
    autoclose : true,
    todayHighlight : true
  };

  $('#start').datepicker( formatCalendar );
  $('#end').datepicker( formatCalendar );
  $('#opd').select2({ placeholder: 'Pilih OPD' });

  $('#peg').select2({
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
          opd: {{ Auth::user()->unker }}          
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
      if (repo.loading) return repo.nama;

      var markup = "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__meta'>" +
          "<div class='select2-result-repository__title'>" + repo.nama + "</div>";

      if (repo.description) {
        markup += "<div class='select2-result-repository__description'>" + repo.nip + "</div>";
      }

      markup += "<div class='select2-result-repository__statistics'>" +
        "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i>NIP: " + repo.nip + "</div>" +
      "</div>" +
      "</div></div>";

      return markup;
  }

  function formatRepoSelection (repo) {
    return repo.nama || repo.nip;
  }

});


</script>
@endpush
