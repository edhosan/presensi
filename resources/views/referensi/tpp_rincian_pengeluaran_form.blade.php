@extends('layouts.app')
@push('css')
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
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
                    <form class="form-horizontal" role="form" method="POST" action="{{ isset($data)? route('tpp.jenis_pengeluaran.update'):route('tpp.rincian_pengeluaran.save') }}" novalidate="novalidate">
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

                       <div class="control-group {{ $errors->has('tahun') ? 'error' : '' }}">
                          <label for="tahun" class="control-label">Tahun</label>

                          <div class="controls">
                            <input type="text" name="tahun" id="tahun" value="{{ $data->tahun or old('tahun') }}">

                              @if ($errors->has('tahun'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('tahun') }}</strong>
                                  </span>
                              @endif
                          </div>
                        </div>

                        <div class="control-group {{ $errors->has('keterangan') ? 'error' : '' }}">
                          <label for="keterangan" class="control-label">Keterangan</label>

                          <div class="controls">
                            <textarea name="keterangan" id="keterangan" cols="50" rows="3">{{ $data->keterangan or old('keterangan') }}</textarea>
                              
                              @if ($errors->has('keterangan'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('keterangan') }}</strong>
                                  </span>
                              @endif
                          </div>
                        </div>
                       
                        <fieldset>
                          <legend>Besaran TPP berdasarkan Lokasi</legend>

                          <div class="control-group {{ $errors->has('lokasi_biasa') ? 'error' : '' }}">
                            <label for="lokasi_biasa" class="control-label">Biasa</label>

                            <div class="controls">
                              <input type="text" name="lokasi_biasa" id="lokasi_biasa" value="{{ $data->lokasi_biasa or old('lokasi_biasa') }}">

                                @if ($errors->has('lokasi_biasa'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lokasi_biasa') }}</strong>
                                    </span>
                                @endif
                            </div>
                          </div>

                          <div class="control-group {{ $errors->has('lokasi_terpencil') ? 'error' : '' }}">
                            <label for="lokasi_terpencil" class="control-label">Terpencil</label>

                            <div class="controls">
                              <input type="text" name="lokasi_terpencil" id="lokasi_terpencil" value="{{ $data->lokasi_terpencil or old('lokasi_terpencil') }}" >

                                @if ($errors->has('lokasi_terpencil'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lokasi_terpencil') }}</strong>
                                    </span>
                                @endif
                            </div>
                          </div>

                          <div class="control-group {{ $errors->has('lokasi_sangat_terpencil') ? 'error' : '' }}">
                            <label for="lokasi_sangat_terpencil" class="control-label">Sangat Terpencil</label>

                            <div class="controls">
                              <input type="text" name="lokasi_sangat_terpencil" id="lokasi_sangat_terpencil" value="{{ $data->lokasi_sangat_terpencil or old('lokasi_sangat_terpencil') }}">

                                @if ($errors->has('lokasi_sangat_terpencil'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lokasi_sangat_terpencil') }}</strong>
                                    </span>
                                @endif
                            </div>
                          </div>
                        </fieldset>                        
                       

                        <div class="form-actions">
                          <button type="submit" class="btn btn-primary">Simpan</button>
                          <a href="{{ route('tpp.rincian_pengeluaran', array($kategori->id,$pengeluaran->id)) }} " class="btn">Batal</a>
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
<script src="{{ asset('autonumeric/autoNumeric.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}" ></script>
<script src="{{ asset('js/bootstrap-datepicker.id.min.js') }}" charset="UTF-8"></script>
<script type="text/javascript">
// The options are...optional :)
const autoNumericOptions = {
    digitGroupSeparator        : '.',
    decimalCharacter           : ',',
    decimalCharacterAlternative: '.',
    currencySymbol             : 'Rp. ',
    currencySymbolPlacement    : AutoNumeric.options.currencySymbolPlacement.prefix,
    roundingMethod             : AutoNumeric.options.roundingMethod.halfUpSymmetric,
};

var lokasi_biasa = new AutoNumeric('#lokasi_biasa', autoNumericOptions);  
var lokasi_terpencil = new AutoNumeric('#lokasi_terpencil', autoNumericOptions);  
var lokasi_sangat_terpencil = new AutoNumeric('#lokasi_sangat_terpencil', autoNumericOptions);  
this.lokasi_biasa.set(0);
this.lokasi_terpencil.set(0);
this.lokasi_sangat_terpencil.set(0);
$('#tahun').datepicker({
  format: "yyyy",
  viewMode: "years", 
  minViewMode: "years"
});
$('#kriteria_id').select2({placeholder: 'Pilih Kriteria',allowClear: true});

</script>
@endpush
