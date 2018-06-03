@extends('layouts.app')
@push('css')
@endpush
@section('content')
<div class="main">
    <div class="main-inner">
        <div class="container">
          <div class="row">
              <div class="span12">
                {!! Breadcrumbs::render('referensi.tpp.create') !!}
                <div class="widget">
                  <div class="widget-header">
                    <i class="icon-file"></i>
                    <h3>Form Kategori TPP</h3>
                  </div>

                  <div class="widget-content">
                    <form class="form-horizontal" role="form" method="POST" action="{{ isset($data)? route('tpp.kategori.update'):route('tpp.kategori.save') }}" novalidate="novalidate">
                      {{ csrf_field() }}
                      <input type="hidden" name="id" value="{{ $data->id or 0 }}">
                      <fieldset>

                        <div class="control-group {{ $errors->has('nm_kategori') ? 'error' : '' }}">
                            <label for="name" class="control-label">Nama Kategori TPP</label>

                            <div class="controls">
                                <textarea name="nm_kategori" id="nm_kategori" cols="30" rows="3" class="span5" >{{ $data->nm_kategori or old('nm_kategori') }}</textarea>                           

                                @if ($errors->has('nm_kategori'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nm_kategori') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>                     

                        <div class="form-actions">
                          <button type="submit" class="btn btn-primary">Simpan</button>
                          <a href="{{ route('tpp.kategori.index') }} " class="btn">Batal</a>
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
