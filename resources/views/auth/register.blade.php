@extends('layouts.app')

@push('css')
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@endpush

@section('content')

<div class="main">
  <div class="main-inner">
    <div class="container">
      {!! Breadcrumbs::render('register') !!}
      <div class="row">
        <div class="span12">
          <div class="widget">
            <div class="widget-header">
              <i class="icon-user"></i>
              <h3>Manajemen User</h3>
              <div class="pull-right">
                <?php $selected_pegawai = isset($data)?$data->username:old('username') ?>
                <select id="pegawai">
                  @if(isset($selected_pegawai))
                    <option value="{{ $selected_pegawai }}" selected="selected">{{ $data->name }}</option>
                  @endif
                </select>
              </div>
            </div>

            <div class="widget-content">
              <form class="form-horizontal" role="form" method="POST" action="{{ isset($data)? route('user_update'):route('register') }}" novalidate="novalidate">
                  {{ csrf_field() }}
                  <input type="hidden" name="id" value="{{ $data->id or 0 }}">
                  <fieldset>
                    <div class="control-group {{ $errors->has('username') ? 'error' : '' }}">
                        <label for="username" class="control-label">Username / NIP</label>

                        <div class="controls">
                            <input id="username" type="text" name="username" value="{{$data->username or old('username') }}" required>

                            @if ($errors->has('username'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="control-group {{ $errors->has('password') ? 'error' : '' }}">
                        <label for="password" class="control-label">Password</label>

                        <div class="controls">
                            <input id="password" type="password" class="span4" name="password" required>

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="control-group">
                        <label for="password-confirm" class="control-label">Confirm Password</label>

                        <div class="controls">
                            <input id="password-confirm" type="password" class="span4" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="control-group {{ $errors->has('name') ? 'error' : '' }}">
                        <label for="name" class="control-label">Nama </label>
                        <div class="controls">
                          <input id="name" type="text" class="span6" name="name" value="{{$data->name or old('name') }}" required>
                          @if ($errors->has('name'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('name') }}</strong>
                              </span>
                          @endif
                        </div>
                    </div>

                    <div class="control-group {{ $errors->has('opd') ? 'error' : '' }}">
                        <label for="type" class="control-label">OPD</label>

                        <div class="controls">
                            <?php $selected_data = isset($data)?$data->unker:old('opd') ?>
                            {{ Form::select('opd', $opd, $selected_data, ['id' => 'opd', 'placeholder' => "Please Select", 'class' => 'span5']) }}

                            @if ($errors->has('opd'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('opd') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="control-group {{ $errors->has('email') ? 'error' : '' }}">
                        <label for="type" class="control-label">E-Mail</label>

                        <div class="controls">
                            <input id="email" type="email" class="span6" name="email" value="{{$data->email or old('email') }}" required>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="control-group {{ $errors->has('phone') ? 'error' : '' }}">
                        <label for="type" class="control-label">No. Telphone</label>

                        <div class="controls">
                            <input id="phone" type="text" class="span6" name="phone" value="{{$data->phone or old('phone') }}">

                            @if ($errors->has('phone'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="control-group {{ $errors->has('tipe') ? 'error' : '' }}">
                        <label for="tipe" class="control-label">Tipe User</label>

                        <div class="controls">
                            <?php $selected_tipe = isset($data)?$data->roles->pluck('id'):old('tipe[]') ?>
                            {{ Form::select('tipe[]', $tipe, $selected_tipe , array('id'=>'tipe', 'multiple'=>'multiple')) }}
                            @if ($errors->has('tipe'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('tipe') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-actions">
                      <button type="submit" class="btn btn-primary">
                          Simpan
                      </button>
                      <a href="{{ route('user') }} " class="btn">Batal</a>
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
  $(function() {
    $('#pegawai').select2({
      placeholder: 'Cari Pegawai',
      allowClear: true,
      ajax: {
        url: "{{ url('api/search_peg?api_token=') }}{{ Auth::user()->api_token }}",
        dataType: 'json',
        delay: 250,
        data: function(params){
          return {
            q: params.term,
            page: params.page,
            per_page: 10
          };
        },
        processResults: function(data, params) {
          params.page = params.page || 1;
          return {
            results: data.data,
            pagination: {
              more: (params.page * data.per_page) < data.total
            }
          };
        },
        cache: true
      },
      escapeMarkup: function( markup ){ return markup; },
      minimumInputLength: 1,
      templateResult: formatRepo,
      templateSelection: formatRepoSelection
    })

    function formatRepo (repo) {
        if (repo.loading) return repo.text;

        var markup = "<div class='select2-result-repository clearfix'>" +
          "<div class='select2-result-repository__meta'>" +
            "<div class='select2-result-repository__title'>" + repo.nama + "</div>";

        if (repo.description) {
          markup += "<div class='select2-result-repository__description'>" + repo.nip + "</div>";
        }

        markup += "<div class='select2-result-repository__statistics'>" +
          "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i>NIP: " + repo.nip + "</div>" +
        "</div>" +
        "</div></div>";

        return markup;
    }

    function formatRepoSelection (repo) {
      return repo.nama || repo.text;
    }

    $('#opd').select2({ placeholder: 'Pilih OPD',allowClear: true });

    $('#tipe').select2({ placeholder: 'Pilih Tipe User' });

    $('#pegawai').on('select2:select', function(e) {
      $('#username').val(e.params.data.nip).trigger("change");
      $('#name').val(e.params.data.nama).trigger("change");;
      $('#opd').val(e.params.data.id_unker).trigger("change");
    });


  });
  </script>
@endpush
