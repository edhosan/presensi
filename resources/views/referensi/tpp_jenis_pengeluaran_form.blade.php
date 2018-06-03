@extends('layouts.app')
@push('css')
@endpush
@section('content')
<div class="main">
    <div class="main-inner">
        <div class="container">
          <div class="row">
              <div class="span12">
                {!! Breadcrumbs::render('referensi.tpp.jenis_pengeluaran.create', $kategori) !!}
                <div class="widget">
                  <div class="widget-header">
                    <i class="icon-file"></i>
                    <h3>Form Jenis Pengeluaran [{{ $kategori->nm_kategori }}]</h3>
                  </div>

                  <div class="widget-content">
                    <form class="form-horizontal" role="form" method="POST" action="{{ isset($data)? route('tpp.kategori.update'):route('tpp.jenis_pengeluaran.save') }}" novalidate="novalidate">
                      {{ csrf_field() }}
                      <input type="hidden" name="id" value="{{ $data->id or 0 }}">
                      <input type="hidden" name="kategori_id" value="{{ $kategori->id }}">
                      <fieldset>
                        <div class="control-group {{ $errors->has('jns_pengeluaran') ? 'error' : '' }}">
                            <label for="name" class="control-label">Nama Jenis Pengeluaran</label>

                            <div class="controls">
                                <input type="text" name="jns_pengeluaran" value="{{ $data->jns_pengeluaran or old('jns_pengeluaran') }}" class="span5">
                                                   
                                @if ($errors->has('jns_pengeluaran'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('jns_pengeluaran') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>  

                        <div class="control-group {{ $errors->has('kriteria') ? 'error' : '' }}">
                            <label for="name" class="control-label">Dihitung berdasarkan</label>

                            <div class="controls">
                                <label class="radio inline">
                                  <input type="radio" name="kriteria" value="ESELON">
                                  Berdasarkan Eselon
                                </label>
                                <label class="radio inline">
                                  <input type="radio" name="kriteria" value="GOLONGAN">
                                  Berdasarkan Pangkat/Golongan
                                </label>
                                <label class="radio inline">
                                  <input type="radio" name="kriteria" value="JABATAN">
                                  Berdasarkan Jabatan Tertentu
                                </label>
                                                   
                                @if ($errors->has('kriteria'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('kriteria') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>                              

                        <div class="form-actions">
                          <button type="submit" class="btn btn-primary">Simpan</button>
                          <a href="{{ route('tpp.jenis_pengeluaran', $kategori->id) }} " class="btn">Batal</a>
                        </div>
                      </fieldset>
                    </form>
                  </div>
                </div>
              </div>
          </div>
        </div>
    </div>
</div>
@endsection
@push('script')
@endpush
