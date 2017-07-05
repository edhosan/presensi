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
                      {{ csrf_field() }}
                      <input type="hidden" name="id" value="{{ $data['id'] or 0 }}">
                      <input type="hidden" name="id_jadwal" value="{{ $data['jadwal_id'] or $jadwal->id }}">
                      <fieldset>
                        <div class="control-group {{ $errors->has('hari') ? 'error' : '' }}">
                            <label for="hari" class="control-label">Hari</label>

                            <div class="controls">
                              @php $selected_data = isset($data)?$data->hari : old('hari') @endphp
                              @if(isset($data))
                                {{ Form::select('hari', $hari, $selected_data, ['id' => 'hari', 'placeholder' => "Please Select", 'disabled' => 'true']) }}
                              @else
                                {{ Form::select('hari', $hari, $selected_data, ['id' => 'hari', 'placeholder' => "Please Select"]) }}
                              @endif

                                @if ($errors->has('hari'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('hari') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="control-group {{ $errors->has('jam_masuk') ? 'error' : '' }}">
                            <label for="jam_masuk" class="control-label">Jam Masuk</label>

                            <div class="controls">
                                <div class="input-append bootstrap-timepicker timepicker">
                                    <input id="jam_masuk" name="jam_masuk" type="text" class="span2 m-wrap" value="{{ $data->jam_masuk or old('jam_masuk') }}">
                                    <button type="button" class="btn"><i class="icon-time "></i></button>
                                </div>

                                @if ($errors->has('jam_masuk'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('jam_masuk') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="control-group {{ $errors->has('jam_pulang') ? 'error' : '' }}">
                            <label for="jam_pulang" class="control-label">Jam Pulang</label>

                            <div class="controls">
                              <div class="input-append bootstrap-timepicker timepicker">
                                  <input id="jam_pulang" name="jam_pulang" type="text" class="span2 m-wrap" value="{{ $data->jam_pulang or old('jam_pulang') }}">
                                  <button type="button" class="btn"><i class="icon-time "></i></button>
                              </div>
                                @if ($errors->has('jam_pulang'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('jam_pulang') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="control-group {{ $errors->has('toleransi_terlambat') ? 'error' : '' }}">
                            <label for="toleransi_terlambat" class="control-label">Toleransi Keterlambatan</label>

                            <div class="controls">
                              <div class="input-append bootstrap-timepicker timepicker">
                                  <input id="toleransi_terlambat" name="toleransi_terlambat" type="text" class="span2 m-wrap" value="{{ $data->toleransi_terlambat or old('toleransi_terlambat') }}">
                                  <button type="button" class="btn"><i class="icon-time "></i></button>
                              </div>

                                @if ($errors->has('toleransi_terlambat'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('toleransi_terlambat') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="control-group {{ $errors->has('toleransi_pulang') ? 'error' : '' }}">
                            <label for="end" class="control-label">Toleransi Pulang Awal</label>

                            <div class="controls">
                              <div class="input-append bootstrap-timepicker timepicker">
                                  <input id="toleransi_pulang" name="toleransi_pulang" type="text" class="span2 m-wrap" value="{{ $data->toleransi_pulang or old('toleransi_pulang') }}">
                                  <button type="button" class="btn"><i class="icon-time "></i></button>
                              </div>

                                @if ($errors->has('toleransi_pulang'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('toleransi_pulang') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-actions">
                          <button type="submit" class="btn btn-primary">Simpan</button>
                          <a href="{{ route('jadwal_list') }} " class="btn">Batal</a>
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
<script src="{{ asset('bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script>
<script type="text/javascript">
$('#jam_masuk').timepicker({ showMeridian: false });
$('#jam_pulang').timepicker({ showMeridian: false });
$('#toleransi_terlambat').timepicker({ showMeridian: false });
$('#toleransi_pulang').timepicker({ showMeridian: false });
</script>
@endpush
