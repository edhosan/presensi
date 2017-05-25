@extends('layouts.app')

@section('content')
<div class="main">
    <div class="main-inner">
        <div class="container">
          <div class="row">
              <div class="span12">
                {!! Breadcrumbs::render('role') !!}
                <div class="widget">
                  <div class="widget-header">
                    <i class="icon-file"></i>
                    <h3>Tipe User</h3>
                  </div>

                  <div class="widget-content">
                    <form class="form-horizontal" role="form" method="POST" action="{{ isset($data)? route('save_update'):route('role_create') }}" novalidate="novalidate">
                      {{ csrf_field() }}
                      <input type="hidden" name="id" value="{{ $data->id or 0 }}">
                      <fieldset>
                        <div class="control-group {{ $errors->has('name') ? 'error' : '' }}">
                            <label for="name" class="control-label">Nama Tipe</label>

                            <div class="controls">
                                <input id="name" type="text" class="span4" name="name" value="{{ $data->name or old('name') }}" autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="control-group {{ $errors->has('display_name') ? 'error' : '' }}">
                            <label for="display_name" class="control-label">Judul</label>

                            <div class="controls">
                                <input id="display_name" type="text" class="span4" name="display_name" value="{{ $data->display_name or old('display_name') }}">

                                @if ($errors->has('display_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('display_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="control-group {{ $errors->has('description') ? 'error' : '' }}">
                            <label for="description" class="control-label">Keterangan</label>

                            <div class="controls">
                                <textarea name="description" class="input-xlarge" rows="3">{{ $data->description or old('description') }}</textarea>

                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="control-group {{ $errors->has('permission') ? 'error' : '' }}">
                            <label for="permission" class="control-label">Hak Akses</label>

                            <div class="controls">
                                <?php $selected_data = isset($data)?$data->perms->pluck('id'):old('permission[]') ?>
                                {{ Form::select('permission[]', $permission, $selected_data , array('multiple'=>'multiple')) }}
                                @if ($errors->has('permission'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('permission') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-actions">
                          <button type="submit" class="btn btn-primary">
                              Simpan
                          </button>
                          <a href="{{ route('role_list') }} " class="btn">Batal</a>
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
