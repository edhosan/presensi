@extends('layouts.app')
@push('css')
<link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="main">
    <div class="main-inner">
        <div class="container">
          <div class="row">
              <div class="span12">
                {!! Breadcrumbs::render('jadwal_form') !!}
                <div class="widget">
                  <div class="widget-header">
                    <i class="icon-file"></i>
                    <h3>Jadwal Kerja</h3>
                  </div>

                  <div class="widget-content">
                    <form class="form-horizontal" role="form" method="POST" action="{{ isset($data)? route('kalendar_update'):route('kalendar_create') }}" novalidate="novalidate">
                      {{ csrf_field() }}
                      <input type="hidden" name="id" value="{{ $data['id'] or 0 }}">
                      <fieldset>
                        <div class="control-group {{ $errors->has('name') ? 'error' : '' }}">
                            <label for="name" class="control-label">Nama Jadwal</label>

                            <div class="controls">
                                <input id="name" type="text" class="span4" name="name" value="{{ $data['name'] or old('name') }}" autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="control-group {{ $errors->has('title') ? 'error' : '' }}">
                            <label for="title" class="control-label">Judul</label>

                            <div class="controls">
                                <input id="title" type="text" class="span4" name="title" value="{{ $data['title'] or old('title') }}">

                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="control-group {{ $errors->has('start') ? 'error' : '' }}">
                            <label for="start" class="control-label">Berlaku Mulai</label>

                            <div class="controls">
                              <input id="start" type="text" name="start" value="{{ $data['start'] or old('start') }}">

                                @if ($errors->has('start'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('start') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="control-group {{ $errors->has('end') ? 'error' : '' }}">
                            <label for="end" class="control-label">Berakhir Sampai</label>

                            <div class="controls">
                              <input id="end" type="text" name="end" value="{{ $data['end'] or old('end') }}">

                                @if ($errors->has('end'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('end') }}</strong>
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
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}" ></script>
<script src="{{ asset('js/bootstrap-datepicker.id.min.js') }}" charset="UTF-8"></script>
<script type="text/javascript">
$('#start').datepicker({ format: 'dd-mm-yyyy', language: 'id' });
$('#end').datepicker({ format: 'dd-mm-yyyy', language: 'id' });
</script>
@endpush
