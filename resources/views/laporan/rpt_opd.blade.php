@extends('layouts.app')
@push('css')
<link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="main">
  <div class="main-inner">
      <div class="container">
          {!! Breadcrumbs::render('laporan.bulanan.view') !!}
          <div class="row">
            <div class="span12">
              <h4 style="text-align:center;">LAPORAN KEHADIRAN</h4>
              <h3 style="text-align:center;">{{ $opd->nama_unker }}</h3>
              <h5 style="text-align:center;">Periode: {{ $start }}&nbsp;-&nbsp;{{ $end }}</h5>
              <br>
              <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <td style="text-align:center">NO</td>
                    <td style="text-align:center">NAMA</td>
                    <td style="text-align:center">NIP</td>
                    <td style="text-align:center">
                      @php
                        
                      @endphp
                    </td>
                  </tr>
                </thead>
              </table>
            </div>
          </div>

      </div>
  </div>
</div>
@endsection

@push('script')
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

});


</script>
@endpush
