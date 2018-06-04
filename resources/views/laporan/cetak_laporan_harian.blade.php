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
          <td align="right" width="5">
            <img src="{{ asset('images/logo.png') }}" alt="" height="62">
          </td>
          <td align="center" colspan="3" >
            <h2>PEMERINTAH KABUPATEN BERAU</h2>
            <h1>{{ $opd->nama_unker }}</h1>
            <h4>LAPORAN KEHADIRAN HARIAN</h4>
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
                  <th rowspan="2" width="5">NO</th>
                  <th rowspan="2">NAMA</th>
                  <th rowspan="2" width="10">NIP</th>
                  <th colspan="2">JAM KERJA</th>
                  <th colspan="2">ABSENSI</th>
                  <th colspan="2">ABSENSI SIANG</th>
                  <th rowspan="2">TERLAMBAT</th>
                  <th rowspan="2">PULANG AWAL</th>
                  <th rowspan="2">KETERANGAN</th>
                </tr>
                <tr class="thick">
                  <th>MASUK</th>
                  <th>PULANG</th>
                  <th>MASUK</th>
                  <th>PULANG</th>
                  <th>I</th>
                  <th>II</th>
                </tr>
                </thead>
                <tbody>
                  @for($i=0;$i<=$interval;$i++)
                    <tr>
                      <td colspan='12' class="sub-title"><h5>Tanggal: {{ $start->format('d-m-Y') }}</h5></td>
                    </tr>
                    @if(!empty($data[$start->day]['event']->event_id))
                      <tr>
                        <td colspan='12'>{{ $data[$start->day]['event']->title }}</td>
                      </tr>
                    @elseif($data[$start->day]['jadwal']->isEmpty())
                      <tr>
                        <td colspan='12'>Hari Libur</td>
                      </tr>
                    @endif
                    @php $no = 1 @endphp
                    @foreach($data[$start->day]['jadwal'] as $value)
                      <tr>
                        <td align="center">{{ $no }}</td>
                        <td>{{ $value->gelar_depan.' '.$value->nama.' '.$value->belakang }}</td>
                        <td align="center">{{ $value->nip }}</td>
                        <td align="center">{{ $value->jam_masuk }}</td>
                        <td align="center">{{ $value->jam_pulang }}</td>
                        <td align="center">{{ $value->in }}</td>
                        <td align="center">{{ $value->out }}</td>
                        <td align="center">{{ $value->scan_1 }}</td>
                        <td align="center">{{ $value->scan_2 }}</td>
                        <td align="center">{{ $value->terlambat }}</td>
                        <td align="center">{{ $value->pulang_awal }}</td>
                        @if(!empty($value->name))
                          <td align="center">{{ $value->name }}</td>
                        @elseif($value->status === 'H')
                          <td align="center">Hadir</td>
                        @elseif($value->status === 'HT')
                          <td align="center">Hadir Terlambat</td>
                        @elseif($value->status === 'HP')
                            <td align="center">Hadir Pulang Awal</td>
                        @elseif($value->status === 'A')
                            <td align="center">Absent</td>
                        @elseif($value->status === 'L')
                            <td align="center">Libur</td>
                        @else
                            <td align="center">N/A</td>
                        @endif
                      </tr>
                      @php $no++ @endphp
                    @endforeach
                    @php $start->addDay() @endphp
                  @endfor
                </tbody>
            </table>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <blockquote>
              <strong>Keterangan:</strong>
              <small>H = Hadir&nbsp;&nbsp;&nbsp;HT = Hadir Terlambat&nbsp;&nbsp;&nbsp;HP = Hadir Pulang Awal&nbsp;&nbsp;&nbsp;A = Absent</small>
              <small>I = Ijin&nbsp;&nbsp;&nbsp;S = Sakit&nbsp;&nbsp;&nbsp;C = Cuti&nbsp;&nbsp;&nbsp;DL = Dinas Luar&nbsp;&nbsp;&nbsp;TB = Tugas Belajar</small>
            </blockquote>
          </td>
        </tr>
        <tr>
           <td align="right" colspan="2">
            <table style="margin-top:50px;margin-left:300px" width="50%">
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
      </tbody>
     
    </table>


@endsection
