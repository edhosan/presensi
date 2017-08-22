@php
  $start = Carbon\Carbon::parse($start);
  $end = Carbon\Carbon::parse($end);
  $interval = $end->diffInDays($start);
  $tanggal = $start->subDay();
@endphp
@extends('layouts.print')
@push('css')
  <style>
    @page { size: F4 landscape }
    h3,
    h4,
    h5    { text-align:center; }
    table { page-break-inside:auto }
    div   { page-break-inside:avoid; } /* This is the key */
    thead { display:table-header-group }
    tfoot { display:table-footer-group }
  </style>
@endpush
@section('content')
<body class="F4 landscape">
  <!-- Each sheet element should have the class "sheet" -->
  <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
  <section class="sheet padding-5mm">
    <h4>LAPORAN KEHADIRAN</h4>
    <h3>{{ $opd->nama_unker }}</h3>
    <h5>Tanggal: {{ $start->format('d-m-Y')  }}&nbsp;s/d&nbsp;{{ $end->format('d-m-Y') }}</h5>
    <br>
    <table>
      <thead>
        <tr>
          <th rowspan="2">NO</th>
          <th rowspan="2">NAMA</th>
          <th rowspan="2">NIP</th>
          <th colspan="2">JAM KERJA</th>
          <th colspan="2">ABSENSI</th>
          <th rowspan="2">TERLAMBAT</th>
          <th rowspan="2">PULANG AWAL</th>
          <th rowspan="2">KETERANGAN</th>
        </tr>
        <tr>
          <th>MASUK</th>
          <th>PULANG</th>
          <th>MASUK</th>
          <th>PULANG</th>
        </tr>
        </thead>
        <tfoot>
            <tr><td>notes</td></tr>
        </tfoot>
        <tbody>
          @for($i=1;$i<=100;$i++)
            <tr style="page-break-inside: avoid;">
              <td colspan='10'><div class="">
               Tanggal: {{ $tanggal->addDay()->format('d-m-Y') }}</div> </td>
            </tr>
          @endfor
        </tbody>
      <tbody>
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
