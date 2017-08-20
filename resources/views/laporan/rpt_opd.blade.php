@php
$month_name = [
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

$date = Carbon\Carbon::createFromDate($tahun, $bulan, 1);
$tot_day = $date->endOfMonth()->day;

@endphp

@extends('layouts.app')
@push('css')
<link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<style>
  .report th{
    text-align: center;
    vertical-align:middle !important;
  }

</style>
@endpush
@section('content')
<div class="main">
  <div class="main-inner">
      <div class="container">
          {!! Breadcrumbs::render('laporan.bulanan.view') !!}
          <div class="row">
            <div class="span12" style="overflow: auto">
              <h4 style="text-align:center;">LAPORAN KEHADIRAN</h4>
              <h3 style="text-align:center;">{{ $opd->nama_unker }}</h3>
              <h5 style="text-align:center;">Bulan {{ $month_name[$bulan]  }}&nbsp;-&nbsp;{{ $tahun }}</h5>
              <br>
              <table class="table table-striped table-bordered report" width="100%">
                <thead>
                  <tr>
                    <th rowspan="2">NO</th>
                    <th rowspan="2">NAMA</th>
                    <th rowspan="2">NIP</th>
                    <th colspan="{{ $tot_day }}">{{ $month_name[$bulan] }}&nbsp;{{ $tahun }}</th>
                    <th colspan="10">JUMLAH</th>
                  </tr>
                  <tr>
                    @for($i=1;$i<=$tot_day;$i++)
                      <th>{{ $i }}</th>
                    @endfor
                    <th>H</th>
                    <th>HT</th>
                    <th>HP</th>
                    <th>I</th>
                    <th>C</th>
                    <th>S</th>
                    <th>DL</th>
                    <th>TB</th>
                    <th>A</th>
                    <th>L</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($data as $item)
                    <tr>
                      <td>{{ $item['no'] }}</td>
                      <td>{{ $item['nama'] }}</td>
                      <td>{{ $item['nip'] }}</td>
                      @for($i=1;$i<=$tot_day;$i++)
                        <td>{{ $item['jadwal'][$i] }}</td>
                      @endfor
                      <td>{{ $item['total']['H'] }}</td>
                      <td>{{ $item['total']['HT'] }}</td>
                      <td>{{ $item['total']['HP'] }}</td>
                      <td>{{ $item['total']['I'] }}</td>
                      <td>{{ $item['total']['C'] }}</td>
                      <td>{{ $item['total']['S'] }}</td>
                      <td>{{ $item['total']['DL'] }}</td>
                      <td>{{ $item['total']['TB'] }}</td>
                      <td>{{ $item['total']['A'] }}</td>
                      <td>{{ $item['total']['L'] }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <blockquote>
                <strong>Keterangan:</strong>
                <small>H = Hadir&nbsp;&nbsp;&nbsp;HT = Hadir Terlambat&nbsp;&nbsp;&nbsp;HP = Hadir Pulang Awal&nbsp;&nbsp;&nbsp;A = Absent</small>
                <small>I = Ijin&nbsp;&nbsp;&nbsp;S = Sakit&nbsp;&nbsp;&nbsp;C = Cuti&nbsp;&nbsp;&nbsp;DL = Dinas Luar&nbsp;&nbsp;&nbsp;TB = Tugas Belajar</small>
              </blockquote>
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
