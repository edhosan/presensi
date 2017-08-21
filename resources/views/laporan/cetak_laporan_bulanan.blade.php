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

@extends('layouts.print')
@push('css')
  <style>
    @page { size: F4 landscape }
    h3,
    h4,
    h5    { text-align:center; }
    .report th{
      text-align: center;
      vertical-align:middle !important;
      font-family:Arial,Verdana,sans-serif;
      font-size:0.55em !important;
    }
    .report td{
      font-family:Arial,Verdana,sans-serif;
      font-size:0.55em !important;
    }
  </style>
@endpush
@section('content')
<body class="F4 landscape">
  <!-- Each sheet element should have the class "sheet" -->
  <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
  <section class="sheet padding-5mm">
    <h4>LAPORAN KEHADIRAN</h4>
    <h3>{{ $opd->nama_unker }}</h3>
    <h5>Bulan {{ $month_name[$bulan]  }}&nbsp;-&nbsp;{{ $tahun }}</h5>
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
  </section>
</body>
@endsection
