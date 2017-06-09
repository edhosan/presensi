@extends('layouts.app')
@push('css')
<link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<link href="{{ asset('easy-autocomplete/dist/easy-autocomplete.min.css') }}" rel="stylesheet">
<link href="{{ asset('easy-autocomplete/dist/easy-autocomplete.themes.min.css') }}" rel="stylesheet">
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
                    <form class="form-horizontal" role="form" method="POST" action="{{ isset($data)? route('jadwal.update'):route('jadwal_create') }}" novalidate="novalidate">
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

                        <div class="control-group {{ $errors->has('id_unker') ? 'error' : '' }}">
                            <label for="opd" class="control-label">Organisasi Perangkat Daerah</label>

                            <div class="controls">
                                <input id="nama_unker" type="text" class="span4 autocomplete" name="nama_unker" value="{{$data->nama_unker or old('nama_unker') }}">
                                <input type="hidden" name="id_unker" value="{{$data->id_unker or old('id_unker') }}" id="id_unker">
                                @if ($errors->has('id_unker'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('id_unker') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="control-group {{ $errors->has('start') ? 'error' : '' }}">
                            <label for="start" class="control-label">Berlaku Mulai</label>

                            <div class="controls">
                              @php
                                $start = old('start');
                                if(!empty($data))
                                  $start = Carbon\Carbon::parse($data['start'])->format('d-m-Y');
                              @endphp
                              <input id="start" type="text" name="start" value="{{ $start }}">

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
                              @php
                                $end = old('end');
                                if(!empty($data))
                                  $end = Carbon\Carbon::parse($data['end'])->format('d-m-Y');
                              @endphp
                              <input id="end" type="text" name="end" value="{{ $end }}">

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
<script src="{{ asset('easy-autocomplete/lib/jquery-1.11.2.min.js') }}"></script>
<script src="{{ asset('easy-autocomplete/dist/jquery.easy-autocomplete.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}" ></script>
<script src="{{ asset('js/bootstrap-datepicker.id.min.js') }}" charset="UTF-8"></script>
<script type="text/javascript">
$('#start').datepicker({ format: 'dd-mm-yyyy', language: 'id' });
$('#end').datepicker({ format: 'dd-mm-yyyy', language: 'id' });

var optUnker = {
    url: function(phrase) {
      return "{{ url('api/unker?api_token=') }}{{ Auth::user()->api_token }}";
    },

    getValue: function(element) {
      return element.nama_unker;
    },

    ajaxSettings: {
      dataType: "json",
      method: "POST",
      data: {
        dataType: "json"
      }
    },

    template: {
      type: "description",
      fields: {
        description: "id_unker"
      }
    },

    list: {
      match: {
        enabled: true
      },
      onSelectItemEvent: function() {
        var value = $("#nama_unker").getSelectedItemData().id_unker;
        $("#id_unker").val(value).trigger("change");
      }
    },

    preparePostData: function(data) {
      data.phrase = $("#nama_unker").val();
      data.unker = "{{ Auth::user()->unker }}";
      return data;
    }
  };

  $("#nama_unker").easyAutocomplete(optUnker);
</script>
@endpush
