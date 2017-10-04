@extends('layouts.app')
@push('css')
<link href="{{ asset('admin_template/js/full-calendar/fullcalendar.css') }}" rel="stylesheet">
<link href="{{ asset('admin_template/css/pages/dashboard.css') }}" rel="stylesheet">
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
            <i class="icon-calendar"></i>
            <h3>Kalendar</h3>
          </div>
          <div class="widget-content">
            <div id="calendar" class="fc"></div>
          </div>
        </div>
      </div>

      <div class="span6">
        <div class="widget-header">
          <i class="icon-bookmark"></i>
          <h3>Tombol Cepat</h3>
        </div>
        <div class="widget-content">
          <div class="shortcuts">
            <a class="shortcut" href="#"><i class="shortcut-icon icon-user"></i><span class="shortcut-label">Manajemen User</span></a>
            <a class="shortcut" href="#"><i class="shortcut-icon icon-group"></i><span class="shortcut-label">Pegawai</span></a>
            <a class="shortcut" href="#"><i class="shortcut-icon icon-time"></i><span class="shortcut-label">Jadwal</span></a>
            <a class="shortcut" href="#"><i class="shortcut-icon icon-exclamation-sign"></i><span class="shortcut-label">Berhalangan Hadir</span></a>
            <a class="shortcut" href="#"><i class="shortcut-icon icon-warning-sign"></i><span class="shortcut-label">Dispensasi</span></a>
            <a class="shortcut" href="#"><i class="shortcut-icon icon-tasks"></i><span class="shortcut-label">Kalkulasi</span></a>
              <a class="shortcut" href="#"><i class="shortcut-icon icon-bar-chart"></i><span class="shortcut-label">Laporan</span></a>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('admin_template/js/full-calendar/fullcalendar.min.js') }}"></script>
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
  });

</script>
@endpush
