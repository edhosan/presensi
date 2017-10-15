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

@extends('layouts.report')
@push('css')
  <style>
    @page { size: F4 landscape }
  </style>
@endpush
@section('content')
<table>
  <thead>
    <tr>
      <td align="right" width="5">
        <img src="{{ asset('images/logo.png') }}" alt="" height="62">
      </td>
      <td align="center">
        <h1>PEMERINTAH KABUPATEN BERAU</h1>
        <h2>{{ $opd->nama_unker }}</h2>
        <h4>REKAPITULASI KEHADIRAN PEGAWAI</h4>
        <h5>Bulan {{ $month_name[$bulan]  }}&nbsp;-&nbsp;{{ $tahun }}</h5>
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
              <th rowspan="2">NO</th>
              <th rowspan="2">NAMA</th>
              <th rowspan="2">NIP</th>
              <th colspan="{{ $tot_day }}">{{ $month_name[$bulan] }}&nbsp;{{ $tahun }}</th>
              <th colspan="10">JUMLAH</th>
            </tr>
            <tr class="thick">
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
              <tr class="thick">
                <td>{{ $item['no'] }}</td>
                <td>{{ $item['nama'] }}</td>
                <td>{{ $item['nip'] }}</td>
                @for($i=1;$i<=$tot_day;$i++)
                  @if(count($item['jadwal']) > 0)
                    <td>
                      @if(array_key_exists($i, $item['jadwal']))
                        {{ $item['jadwal'][$i] }}
                      @endif
                    </td>
                  @else
                    <td></td>
                  @endif
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
      </td>
    </tr>
  </tbody>
</table>
<blockquote>
  <strong>Keterangan:</strong>
  <small>H = Hadir&nbsp;&nbsp;&nbsp;HT = Hadir Terlambat&nbsp;&nbsp;&nbsp;HP = Hadir Pulang Awal&nbsp;&nbsp;&nbsp;A = Absent</small>
  <small>I = Ijin&nbsp;&nbsp;&nbsp;S = Sakit&nbsp;&nbsp;&nbsp;C = Cuti&nbsp;&nbsp;&nbsp;DL = Dinas Luar&nbsp;&nbsp;&nbsp;TB = Tugas Belajar</small>
</blockquote>
@endsection
