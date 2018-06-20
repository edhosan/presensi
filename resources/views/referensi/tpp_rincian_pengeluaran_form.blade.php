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
                    <form class="form-horizontal" role="form" method="POST" action="{{ isset($data)? route('tpp.jenis_pengeluaran.update'):route('tpp.rincian_pengeluaran.save') }}" novalidate="novalidate">
                      {{ csrf_field() }}
                      <input type="hidden" name="id" value="{{ $data->id or 0 }}">
                      <input type="hidden" name="kategori_id" value="{{ $kategori->id }}">
                      <input type="hidden" name="tpp_jenis_pengeluaran_id" value="{{ $pengeluaran->id }}">
                      <input type="hidden" name="kriteria_name" id="kriteria_name" value="{{ old('kriteria_name') }}">
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

                        <div class="control-group {{ $errors->has('lokasi_biasa') ? 'error' : '' }}">
                            <label for="lokasi_biasa" class="control-label">Lokasi Biasa</label>

                            <div class="controls">                            
                                <input type="text" class="span2 allow_numeric" name="lokasi_biasa" value="0" >    
                                @if ($errors->has('lokasi_biasa'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lokasi_biasa') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                         <div class="control-group {{ $errors->has('lokasi_terpencil') ? 'error' : '' }}">
                            <label for="lokasi_terpencil" class="control-label">Lokasi Terpencil</label>

                            <div class="controls">                            
                                <input type="text" class="span2 allow_numeric" name="lokasi_terpencil" value="0">    
                                @if ($errors->has('lokasi_terpencil'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lokasi_terpencil') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>                  

                        <div class="control-group {{ $errors->has('lokasi_sangat_terpencil') ? 'error' : '' }}">
                            <label for="lokasi_sangat_terpencil" class="control-label">Lokasi Sangat Terpencil</label>

                            <div class="controls">                            
                                <input type="text" class="span2 allow_numeric" name="lokasi_sangat_terpencil" value="0">    
                                @if ($errors->has('lokasi_sangat_terpencil'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lokasi_sangat_terpencil') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>                

                        <div class="control-group {{ $errors->has('tahun') ? 'error' : '' }}">
                            <label for="tahun" class="control-label">Tahun</label>

                            <div class="controls">                            
                                <input type="text" class="span1 allow_numeric" name="tahun" value="{{ date('Y') }}">    
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
                                <textarea name="keterangan" id="keterangan" cols="30" rows="3" class="span5"></textarea>   
                                @if ($errors->has('keterangan'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('keterangan') }}</strong>
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
$(".allow_numeric").on("input", function(evt) {
   var self = $(this);
   self.val(self.val().replace(/[^\d].+/, ""));
   if ((evt.which < 48 || evt.which > 57)) 
   {
     evt.preventDefault();
   }
 });

$('#kriteria_id').on('change', function(e){ 
  var textSelected = $("#kriteria_id :selected").text();
  $('#kriteria_name').val(textSelected); 
});
</script>
@endpush
