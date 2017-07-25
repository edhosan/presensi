@extends('layouts.app')
@push('css')
@endpush
@section('content')
<div class="main">
    <div class="main-inner">
        <div class="container">
          <div class="row">
              <div class="span12">
                {!! Breadcrumbs::render('ref_ijin.form') !!}
                <div class="widget">
                  <div class="widget-header">
                    <i class="icon-file"></i>
                    <h3>Form Data Referensi Keterangan Tidak Masuk</h3>
                  </div>

                  <div class="widget-content">
                    <form class="form-horizontal" role="form" method="POST" action="{{ isset($data)? route('ref_ijin.update'):route('ref_ijin.store') }}" novalidate="novalidate">
                      {{ csrf_field() }}
                      <input type="hidden" name="id" value="{{ $data->id or 0 }}">
                      <fieldset>

                        <div class="control-group {{ $errors->has('name') ? 'error' : '' }}">
                            <label for="name" class="control-label">Nama Keterangan</label>

                            <div class="controls">
                                <input id="name" name="name" type="text" class="span4" value="{{ $data->name or old('name') }}">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-actions">
                          <button type="submit" class="btn btn-primary">Simpan</button>
                          <a href="{{ route('ref_ijin.list') }} " class="btn">Batal</a>
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
