@extends('layouts.app')
@push('css')
<link href="{{ asset('bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="main">
    <div class="main-inner">
        <div class="container">
          <div class="row">
              <div class="span12">
                {!! Breadcrumbs::render('hari') !!}
                <div class="widget">
                  <div class="widget-header">
                    <i class="icon-file"></i>
                    <h3>Hari Kerja</h3>
                  </div>
  
                  <div class="widget-content">
                    <form class="form-horizontal" role="form" method="POST" action="{{ isset($data)? route('hari.update'):route('hari.store') }}" novalidate="novalidate">
                 
                      <div class="row">
                        <div class="span12">
                          <fieldset>
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{ $data['id'] or 0 }}">
                            <input type="hidden" name="id_jadwal" value="{{ $data['jadwal_id'] or $jadwal->id }}"> 
                            <div class="span6">
                              <fieldset>
                                <legend>Waktu Kerja</legend>
                                <div class="control-group {{ $errors->has('hari') ? 'error' : '' }}">
                                  <label for="hari" class="control-label">Hari</label>

                                  <div class="controls">
                                    @php $selected_data = isset($data)?$data->hari : old('hari') @endphp
                                    @if(isset($data))
                                      {{ Form::select('hari', $hari, $selected_data, ['id' => 'hari', 'placeholder' => "Please Select", 'disabled' => 'true', 'class' => 'span1']) }}
                                    @else
                                      {{ Form::select('hari', $hari, $selected_data, ['id' => 'hari', 'placeholder' => "Please Select", 'class' => 'span1']) }}
                                    @endif

                                      @if ($errors->has('hari'))
                                          <span class="help-block">
                                              <strong>{{ $errors->first('hari') }}</strong>
                                          </span>
                                      @endif
                                  </div>
                                </div> <!-- end div hari kerja -->


                                <div class="control-group {{ $errors->has('jam_masuk') ? 'error' : '' }} {{ $errors->has('jam_pulang') ? 'error' : '' }}">
                                  <label for="jam_masuk" class="control-label">Waktu Kerja</label>

                                  <div class="controls">
                                      <div class="input-append bootstrap-timepicker timepicker">
                                          <input id="jam_masuk" name="jam_masuk" type="text" class="span1 m-wrap" value="{{ $data->jam_masuk or old('jam_masuk') }}">
                                          <button type="button" class="btn"><i class="icon-time "></i></button>
                                      </div>
                                      <span>-</span>
                                      <div class="input-append bootstrap-timepicker timepicker">
                                        <input id="jam_pulang" name="jam_pulang" type="text" class="span1 m-wrap" value="{{ $data->jam_pulang or old('jam_pulang') }}">
                                        <button type="button" class="btn"><i class="icon-time "></i></button>
                                      </div>
                                      <p class="help-block">
                                        *Diisi jam masuk dan pulang kerja
                                      </p>

                                      @if ($errors->has('jam_masuk'))
                                          <span class="help-block">
                                              <strong>{{ $errors->first('jam_masuk') }}</strong>
                                          </span>
                                      @endif

                                      @if ($errors->has('jam_pulang'))
                                          <span class="help-block">
                                              <strong>{{ $errors->first('jam_pulang') }}</strong>
                                          </span>
                                      @endif
                                  </div>                              
                                </div> <!-- End Div Waktu Kerja -->

                                <div class="control-group {{ $errors->has('toleransi_terlambat') ? 'error' : '' }}">
                                  <label for="toleransi_terlambat" class="control-label">Toleransi Terlambat</label>

                                  <div class="controls">
                                    <div class="input-append bootstrap-timepicker timepicker">
                                      <input id="toleransi_terlambat" name="toleransi_terlambat" type="text" class="span1 m-wrap" value="{{ $data->toleransi_terlambat or old('toleransi_terlambat') }}">
                                      <button type="button" class="btn"><i class="icon-time "></i></button>
                                    </div>

                                    @if ($errors->has('toleransi_terlambat'))
                                      <span class="help-block">
                                        <strong>{{ $errors->first('toleransi_terlambat') }}</strong>
                                      </span>
                                    @endif
                                  </div>
                                </div> <!-- End div toleransi terlambat -->

                                <div class="control-group {{ $errors->has('toleransi_pulang') ? 'error' : '' }}">
                                  <label for="end" class="control-label">Toleransi Pulang Awal</label>

                                  <div class="controls">
                                    <div class="input-append bootstrap-timepicker timepicker">
                                        <input id="toleransi_pulang" name="toleransi_pulang" type="text" class="span1 m-wrap" value="{{ $data->toleransi_pulang or old('toleransi_pulang') }}">
                                        <button type="button" class="btn"><i class="icon-time "></i></button>
                                    </div>

                                    @if ($errors->has('toleransi_pulang'))
                                      <span class="help-block">
                                        <strong>{{ $errors->first('toleransi_pulang') }}</strong>
                                      </span>
                                    @endif
                                  </div>
                                </div> <!-- End Div Toleransi Pulang -->

                              </fieldset> <!-- End Fieldset -->

                              <fieldset>
                                <legend>Batas Waktu Absensi</legend>
                                <div class="control-group {{ $errors->has('scan_in1') ? 'error' : '' }} {{ $errors->has('scan_in2') ? 'error' : '' }}">
                                  <label for="scan_in1" class="control-label">Absensi Masuk</label>

                                  <div class="controls">
                                    <div class="input-append bootstrap-timepicker timepicker">
                                        <input id="scan_in1" name="scan_in1" type="text" class="span1 m-wrap" value="{{ $data->scan_in1 or old('scan_in1') }}">
                                        <button type="button" class="btn"><i class="icon-time "></i></button>
                                    </div>
                                    &nbsp;s/d&nbsp;
                                    <div class="input-append bootstrap-timepicker timepicker">
                                        <input id="scan_in2" name="scan_in2" type="text" class="span1 m-wrap" value="{{ $data->scan_in2 or old('scan_in2') }}">
                                        <button type="button" class="btn"><i class="icon-time "></i></button>
                                    </div>
                                    <p class="help-block">
                                       *Diisi batas waktu untuk absensi masuk
                                    </p>

                                    @if ($errors->has('scan_in1'))
                                      <span class="help-block">
                                        <strong>{{ $errors->first('scan_in1') }}</strong>
                                      </span>
                                    @endif

                                    @if ($errors->has('scan_in2'))
                                      <span class="help-block">
                                        <strong>{{ $errors->first('scan_in2') }}</strong>
                                      </span>
                                    @endif
                                  </div>
                                </div>

                                <div class="control-group {{ $errors->has('scan_out1') ? 'error' : '' }} {{ $errors->has('scan_out2') ? 'error' : '' }}">
                                  <label for="scan_out1" class="control-label">Absensi Pulang</label>

                                  <div class="controls">
                                    <div class="input-append bootstrap-timepicker timepicker">
                                      <input id="scan_out1" name="scan_out1" type="text" class="span1 m-wrap" value="{{ $data->scan_out1 or old('scan_out1') }}">
                                        <button type="button" class="btn"><i class="icon-time "></i></button>
                                    </div>
                                    &nbsp;s/d&nbsp;
                                    <div class="input-append bootstrap-timepicker timepicker">
                                        <input id="scan_out2" name="scan_out2" type="text" class="span1 m-wrap" value="{{ $data->scan_out2 or old('scan_out2') }}">
                                        <button type="button" class="btn"><i class="icon-time "></i></button>
                                    </div>
                                    <p class="help-block">
                                       *Diisi batas waktu untuk absensi pulang
                                    </p>

                                    @if ($errors->has('scan_out1'))
                                      <span class="help-block">
                                        <strong>{{ $errors->first('scan_out1') }}</strong>
                                      </span>
                                    @endif

                                    @if ($errors->has('scan_out2'))
                                      <span class="help-block">
                                        <strong>{{ $errors->first('scan_out2') }}</strong>
                                      </span>
                                    @endif
                                  </div>
                                </div>                            
                              </fieldset>

                            </div> 

                            <div class="span5">
                        
                            <fieldset>
                              <fieldset>
                              <legend>Absensi Siang</legend>

                              <div class="control-group">
                                <label for="absensi_siang" class="control-label">Gunakan Absensi Siang</label>

                                <div class="controls">
                                  <label class="checkbox inline">
                                    <input type="checkbox" id="absensi_siang_check" name="absensi_siang_check" value="1" 
                                      @if(old('absensi_siang_check') == "1" || $data->is_siang_absensi == 1) checked @endif
                                    > Ya
                                  </label>
                                </div>
                              </div>

                              <div id="absensi_siang_controls">
                                <div class="control-group {{ $errors->has('absensi_siang_out1') ? 'error' : '' }} {{ $errors->has('absensi_siang_out2') ? 'error' : '' }}">
                                  <label for="absensi_siang_out1" class="control-label">Absensi Siang 1</label>
                                  <div class="controls">
                                    <div class="input-append bootstrap-timepicker timepicker">
                                        <input id="absensi_siang_out1" name="absensi_siang_out1" type="text" class="span1 m-wrap" value="{{ $data->absensi_siang_out1 or old('absensi_siang_out1') }}">
                                        <button type="button" class="btn"><i class="icon-time "></i></button>
                                    </div>
                                    &nbsp;s/d&nbsp;
                                    <div class="input-append bootstrap-timepicker timepicker">
                                        <input id="absensi_siang_out2" name="absensi_siang_out2" type="text" class="span1 m-wrap" value="{{ $data->absensi_siang_out2 or old('absensi_siang_out2') }}">
                                        <button type="button" class="btn"><i class="icon-time "></i></button>
                                    </div>

                                      @if ($errors->has('absensi_siang_out1'))
                                          <span class="help-block">
                                              <strong>{{ $errors->first('absensi_siang_out1') }}</strong>
                                          </span>
                                      @endif

                                      @if ($errors->has('absensi_siang_out2'))
                                          <span class="help-block">
                                              <strong>{{ $errors->first('absensi_siang_out2') }}</strong>
                                          </span>
                                      @endif
                                  </div>

                                </div>

                                <div class="control-group {{ $errors->has('absensi_siang_in1') ? 'error' : '' }} {{ $errors->has('absensi_siang_in2') ? 'error' : '' }}">
                                  <label for="absensi_siang_in1" class="control-label">Absensi Siang 2</label>
                                  <div class="controls">
                                    <div class="input-append bootstrap-timepicker timepicker">
                                        <input id="absensi_siang_in1" name="absensi_siang_in1" type="text" class="span1 m-wrap" value="{{ $data->absensi_siang_in1 or old('absensi_siang_in1') }}">
                                        <button type="button" class="btn"><i class="icon-time "></i></button>
                                    </div>
                                    &nbsp;s/d&nbsp;
                                    <div class="input-append bootstrap-timepicker timepicker">
                                        <input id="absensi_siang_in2" name="absensi_siang_in2" type="text" class="span1 m-wrap" value="{{ $data->absensi_siang_in2 or old('absensi_siang_in2') }}">
                                        <button type="button" class="btn"><i class="icon-time "></i></button>
                                    </div>

                                      @if ($errors->has('absensi_siang_in1'))
                                          <span class="help-block">
                                              <strong>{{ $errors->first('absensi_siang_in1') }}</strong>
                                          </span>
                                      @endif

                                      @if ($errors->has('absensi_siang_in2'))
                                          <span class="help-block">
                                              <strong>{{ $errors->first('absensi_siang_in2') }}</strong>
                                          </span>
                                      @endif
                                  </div>

                                </div>

  
                              </div>
                            </fieldset>

                        </fieldset>

                        </div>


                      </fieldset>
                        <div class="form-actions">
                          <button type="submit" class="btn btn-primary">Simpan</button>
                          <a href="{{ route('jadwal_list') }}" class="btn">Batal</a>
                        </div>

                      </div>


                      </div>


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
<script src="{{ asset('bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script>
<script type="text/javascript">
$('#jam_masuk').timepicker({ showMeridian: false, icons:{ up: 'icon icon-caret-up', down: 'icon icon-caret-down' } });
$('#jam_pulang').timepicker({ showMeridian: false, icons:{ up: 'icon icon-caret-up', down: 'icon icon-caret-down' } });
$('#toleransi_terlambat').timepicker({ showMeridian: false, icons:{ up: 'icon icon-caret-up', down: 'icon icon-caret-down' } });
$('#toleransi_pulang').timepicker({ showMeridian: false, icons:{ up: 'icon icon-caret-up', down: 'icon icon-caret-down' } });
$('#scan_in1').timepicker({ showMeridian: false, icons:{ up: 'icon icon-caret-up', down: 'icon icon-caret-down' } });
$('#scan_in2').timepicker({ showMeridian: false, icons:{ up: 'icon icon-caret-up', down: 'icon icon-caret-down' } });
$('#scan_out1').timepicker({ showMeridian: false, icons:{ up: 'icon icon-caret-up', down: 'icon icon-caret-down' } });
$('#scan_out2').timepicker({ showMeridian: false, icons:{ up: 'icon icon-caret-up', down: 'icon icon-caret-down' } });
$('#absensi_siang_out1').timepicker({ showMeridian: false, icons:{ up: 'icon icon-caret-up', down: 'icon icon-caret-down' } });
$('#absensi_siang_out2').timepicker({ showMeridian: false, icons:{ up: 'icon icon-caret-up', down: 'icon icon-caret-down' } });
$('#absensi_siang_in1').timepicker({ showMeridian: false, icons:{ up: 'icon icon-caret-up', down: 'icon icon-caret-down' } });
$('#absensi_siang_in2').timepicker({ showMeridian: false, icons:{ up: 'icon icon-caret-up', down: 'icon icon-caret-down' } });

@if(isset($data))
  @if($data->is_siang_absensi == 1)
    disableControlAbsensi(false);
  @else
    disableControlAbsensi(true);
  @endif
@else
  disableControlAbsensi(true);
@endif

function disableControlAbsensi(is_disabled){
  $('#absensi_siang_out1').prop('disabled', is_disabled);
  $('#absensi_siang_out2').prop('disabled', is_disabled);
  $('#absensi_siang_in1').prop('disabled', is_disabled);
  $('#absensi_siang_in2').prop('disabled', is_disabled);
}

$("#absensi_siang_check").change(function() {
  if(this.checked) {
    disableControlAbsensi(false);
  }else{
    disableControlAbsensi(true);
  }
});

</script>
@endpush
