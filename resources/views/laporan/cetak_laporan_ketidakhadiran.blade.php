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
    <tr valign="middle">
      <td align="right" width="5">
        <img src="{{ asset('images/logo.png') }}" alt="" height="62">
      </td>
      <td align="center">
        <h3>PEMERINTAH KABUPATEN BERAU</h3>
        <h1>{{ $opd->nama_unker }}</h1>
        <h4>LAPORAN KETIDAKHADIRAN PEGAWAI</h4>
        <h5>Tanggal: {{ $start->format('d-m-Y')  }}&nbsp;s/d&nbsp;{{ $end->format('d-m-Y') }}</h5>
        <br>
      </td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td align="center" colspan="2">
        <table class="border thick data">
          <thead>
            <tr class="thick">
              <th width="5">NO</th>
              <th>TANGGAL</th>
              <th>NAMA</th>
              <th>NIP</th>
              <th>KETERANGAN</th>
              <th>ALASAN TIDAK HADIR</th>
            </tr>
          </thead>
          <tbody>
            @php $no = 1 @endphp
            @foreach($data as $value)
              <tr>
                <td align="center">{{ $no }}</td>
                <td align="center">{{ $value->tanggal }}</td>
                <td>{{ $value->nama }}</td>
                <td align="center">{{ $value->nip }}</td>
                <td align="center">{{ $value->name }}</td>
                <td>{{ $value->keperluan }}</td>
              </tr>
            @php $no++ @endphp
            @endforeach
          </tbody>         
        </table>
      </td>
    </tr>
  </tbody>
  <tfoot>
      <tr>
        <td></td>
        <td>
           <table style="margin-top:50px; margin-left: 400px" width="25%" border="0">
            <tr align="center">
              <td>Tanjung Redeb, {{ Carbon\Carbon::now()->day }} {{ monthName(Carbon\Carbon::now()->month) }} {{ Carbon\Carbon::now()->year }}</td>
            </tr>
            <tr align="center">
              <td>MENGETAHUI</td>
            </tr>
            <tr align="center">
              <td>KEPALA</td>
            </tr>
            <tr align="center" style="margin: 100px">            
              <td>
                <div style="margin-top: 50px">
                  <strong>
                  <u>
                    @if(isset($kepala))
                      {{ $kepala->nama }}
                    @endif
                  </u>
                  </strong>
                </div>
              </td>            
            </tr>   
            <tr align="center">
              <td>
                @if(isset($kepala))
                  NIP. {{ $kepala->nip }}
                @endif
              </td>
            </tr>       
          </table>
        </td>
      </tr>

    </tfoot>
</table>
@endsection
