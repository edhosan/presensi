@php
  $start = Carbon\Carbon::parse($start);
  $end = Carbon\Carbon::parse($end);
  $interval = $end->diffInDays($start);

@endphp
@extends('layouts.report')
@push('css')
  <style>
    @page { size: F4 landscape }
    .sub-title { background:#eee; }
  </style>
@endpush
@section('content')
<table>
  <thead>
    <tr>
      <td align="center">
        <h4>LAPORAN KETIDAKHADIRAN</h4>
        <h3>{{ $opd->nama_unker }}</h3>
        <h5>Tanggal: {{ $start->format('d-m-Y')  }}&nbsp;s/d&nbsp;{{ $end->format('d-m-Y') }}</h5>
        <br>
      </td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td align="center">
        <table class="border thick data">
          <thead>
            <tr class="thick">
              <th width="5">NO</th>
              <th>NAMA</th>
              <th>NIP</th>
              <th>TANGGAL</th>
              <th>KETERANGAN</th>
              <th>ALASAN TIDAK HADIR</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td></td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </tbody>
</table>
@endsection
