@extends('layouts.app')
@push('css')
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@endpush
@section('content')
@if(Auth::check())
   @include('partial.subnavbar')
@endif
<div class="main">
  <div class="main-inner">
      <div class="container">
          {!! Breadcrumbs::render('laporan.ketidakhadiran') !!}
          <div class="row">
            <div class="span12">
              <div class="widget">
                <div class="widget-header">
                  <i class="icon-file"></i>
                  <h3>Cetak Laporan Ketidakhadiran Pegawai</h3>
                </div>

                <div class="widget-content">
                  <form class="form-horizontal" target="_blank" role="form" method="POST" id="form" action="{{ route('cetak.laporan.ketidakhadiran') }}" novalidate="novalidate">
                    {{ csrf_field() }}
                    <fieldset>
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
@endsection

@push('script')
<script src="{{ asset('easy-autocomplete/lib/jquery-1.11.2.min.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}" ></script>
<script src="{{ asset('js/bootstrap-datepicker.id.min.js') }}" charset="UTF-8"></script>
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
  $('#opd').select2({
    placeholder: "Pilih OPD",
    allowClear: true
  });



});


</script>
@endpush
