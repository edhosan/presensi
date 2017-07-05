@extends('layouts.app')
@push('css')
<link href="{{ asset('admin_template/js/full-calendar/fullcalendar.css') }}" rel="stylesheet">
@endpush
@section('content')
  <div class="main">
    <div class="main-inner">
      <div class="container">
        <div class="row">
          <div class="span12">
            {!! Breadcrumbs::render('peg_jadwal.detail') !!}
            <div class="widget">
              <div class="widget-header">
                <i class="icon-file"></i>
                <h3>Rincian Jadwal Pegawai</h3>
              </div>

              <div class="widget-content">
                <div id="calendar" class="fc"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('script')
<script src="{{ asset('admin_template/js/full-calendar/fullcalendar.min.js') }}"></script>
<script>
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
       events: {
         url: '{{ url("api/peg_jadwal_detail?api_token=") }}{{ Auth::user()->api_token }}',
         type: 'POST',
         data: {
           peg_id: '{{ $id }}'
         },
         error: function() {
              alert('there was an error while fetching events!');
          },
       }
     });
});
</script>
@endpush
