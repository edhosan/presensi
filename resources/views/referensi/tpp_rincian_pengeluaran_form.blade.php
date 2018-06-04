@extends('layouts.app')
@push('css')
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="main">
    <div class="main-inner">
        <div class="container">
          <div class="row">
              <div class="span12">
                {!! Breadcrumbs::render('referensi.tpp.rincian_pengeluaran.create', $kategori, $pengeluaran) !!}
                <div class="widget">
                  <div class="widget-header">
                    <i class="icon-file"></i>
                    <h3>Form Rincian Pengeluaran [{{ $pengeluaran->jns_pengeluaran }}]</h3>
                  </div>

                  <div class="widget-content">
                    <form class="form-horizontal" role="form" method="POST" action="{{ isset($data)? route('tpp.jenis_pengeluaran.update'):route('tpp.jenis_pengeluaran.save') }}" novalidate="novalidate">
                      {{ csrf_field() }}
                      <input type="hidden" name="id" value="{{ $data->id or 0 }}">
                      <input type="hidden" name="kategori_id" value="{{ $kategori->id }}">
                      <input type="hidden" name="tpp_jenis_pengeluaran_id" value="{{ $pengeluaran->id }}">
                      <fieldset>
                        <div class="control-group {{ $errors->has('kriteria_id') ? 'error' : '' }}">
                            <label for="kriteria_id" class="control-label">Kriteria</label>

                            <div class="controls">
                              <?php $selected_kriteria = isset($data)?$data->kriteria_id:old('kriteria_id') ?>
                              {{ Form::select('kriteria_id', $kriteria, $selected_kriteria, ['id' => 'kriteria_id', 'placeholder' => "Please Select", 'class' => 'span4']) }}

                                @if ($errors->has('kriteria_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('kriteria_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="control-group {{ $errors->has('kriteria') ? 'error' : '' }}">
                            <label for="name" class="control-label">Dihitung berdasarkan</label>

                            <div class="controls">
                                <label class="radio inline">                              
                                  <input type="radio" name="kriteria" value="ESELON" 
                                    @if(isset($data))
                                      {{ $data->kriteria == "ESELON"?'checked':'' }}
                                    @endif
                                  >
                                  Eselon
                                </label>
                                <label class="radio inline">
                                  <input type="radio" name="kriteria" value="GOLONGAN"     
                                    @if(isset($data))
                                      {{ $data->kriteria == "GOLONGAN"?'checked':'' }}
                                    @endif
                                    >
                                  Pangkat/Golongan
                                </label>
                                <label class="radio inline">
                                  <input type="radio" name="kriteria" value="JABATAN"}  
                                    @if(isset($data))
                                      {{ $data->kriteria == "JABATAN"?'checked':'' }}
                                    @endif
                                    >
                                  Jabatan Tertentu
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
<script src="{{ asset('js/select2.min.js') }}"></script>
<script type="text/javascript">
$('#kriteria_id').select2({placeholder: 'Pilih Kriteria',allowClear: true});
</script>
@endpush
