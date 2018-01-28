@extends('layouts.app')
@push('css')
<link href="{{ asset('admin_template/js/full-calendar/fullcalendar.css') }}" rel="stylesheet">
<link href="{{ asset('admin_template/css/pages/dashboard.css') }}" rel="stylesheet">
<link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@endpush
@section('content')
@if(Auth::check())
   @include('partial.subnavbar')
@endif

<div class="container">
    <div class="row">
      <div class="span6">
        <div class="widget">
          <div class="widget-header">
              <i class="icon-bar-chart"></i>
              <h3>Persentase Kehadiran</h3>
          </div>
          <div class="widget-content">
            <form class="form-search" role="form" method="POST" action="{{ route('piechart.filter') }}" novalidate="novalidate">
              @php
                $start = old('start');
                if(!empty($tanggal))
                  $start = Carbon\Carbon::parse($tanggal['start'])->format('d-m-Y');
              @endphp
              <input id="start" type="text" class="span2" name="start" value="{{ $start }}">
              &nbsp;s/d&nbsp;
              @php
                $end = old('end');
                if(!empty($tanggal))
                  $end = Carbon\Carbon::parse($tanggal['end'])->format('d-m-Y');
              @endphp
              <input id="end" type="text" class="span2" name="end" value="{{ $end }}">
              <button type="submit" class="btn btn-primary">Filter</button>
            </form>

            {!! $chartjs->render() !!}

            <p class="muted">Jumlah Pegawai: {{ $jumlahpegawai }}&nbsp;&nbsp;&nbsp;Hari Efektif: {{ $jumlahhariefektif }}</p>
          </div>
        </div>
      </div>

      <div class="span6">
        <div class="widget">
          <div class="widget-header">
            <i class="icon-bookmark"></i>
            <h3>Tombol Cepat</h3>
          </div>
          <div class="widget-content">
            <div class="shortcuts">
              <a class="shortcut" href="{{ route('user') }}"><i class="shortcut-icon icon-user"></i><span class="shortcut-label">Manajemen User</span></a>
              <a class="shortcut" href="{{ route('datainduk_list') }}"><i class="shortcut-icon icon-group"></i><span class="shortcut-label">Pegawai</span></a>
              <a class="shortcut" href="{{ route('peg_jadwal.list') }}"><i class="shortcut-icon icon-time"></i><span class="shortcut-label">Jadwal</span></a>
              <a class="shortcut" href="{{ route('ketidakhadiran.list') }}"><i class="shortcut-icon icon-exclamation-sign"></i><span class="shortcut-label">Berhalangan Hadir</span></a>
              <a class="shortcut" href="{{ route('dispensasi.list') }}"><i class="shortcut-icon icon-warning-sign"></i><span class="shortcut-label">Dispensasi</span></a>
              <a class="shortcut" href="{{ route('kalkulasi.form') }}"><i class="shortcut-icon icon-tasks"></i><span class="shortcut-label">Kalkulasi Kehadiran</span></a>
                <a class="shortcut" href="{{ route('laporan.bulanan') }}"><i class="shortcut-icon icon-bar-chart"></i><span class="shortcut-label">Laporan Bulanan</span></a>
            </div>
          </div>
        </div>

        <div class="widget">
          <div class="widget-header">
            <i class="icon-calendar"></i>
            <h3>Kalendar</h3>
          </div>
          <div class="widget-content">
            <div id="calendar" class="fc"></div>
          </div>
        </div>

      </div>
    </div>

    <div class="row">
      <div class="span12">
      

      </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('admin_template/js/full-calendar/fullcalendar.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}" ></script>
<script src="{{ asset('js/bootstrap-datepicker.id.min.js') }}" charset="UTF-8"></script>
<script type="text/javascript">
  $(document).ready(function() {
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    var calendar = $('#calendar').fullCalendar({
       header: {
         left: 'prev,next today',
         center: 'title',
         right: 'month,agendaWeek,agendaDay'
       },
       selectable: true,
       selectHelper: true,
       editable: true,
       events: '{{ url("api/kalendar_list?api_token=") }}{{ Auth::user()->api_token }}'
     });

     var formatCalendar = {
       format: 'dd-mm-yyyy',
       language: 'id',
       autoclose : true,
       todayHighlight : true
     };

     $('#start').datepicker( formatCalendar );
     $('#end').datepicker( formatCalendar );
  });

</script>
@endpush
