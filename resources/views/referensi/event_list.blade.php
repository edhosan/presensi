@extends('layouts.app')
@push('css')
<link href="{{ asset('admin_template/js/full-calendar/fullcalendar.css') }}" rel="stylesheet">
@endpush
@section('content')
@if(Auth::check())
   @include('partial.subnavbar')
@endif
<div class="main">
  <div class="main-inner">
      <div class="container">
          {!! Breadcrumbs::render('kalendar_list') !!}
          <div class="row">
            <div class="span12">
              <div class="widget">
                <div class="widget-header">
                  <i class="icon-list"></i>
                  <h3>Kalendar Kerja</h3>
                </div>

                <div class="widget-content">
                  <a href="{{ route('kalendar_create') }} " class="btn btn-primary"><i class="icon-plus"> Tambah Data</i></a>
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
