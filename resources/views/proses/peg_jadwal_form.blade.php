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
            {!! Breadcrumbs::render('peg_jadwal.form') !!}
            <div class="widget">
              <div class="widget-header">
                <i class="icon-file"></i>
                <h3>Form Jadwal Pegawai</h3>
              </div>

              <div class="widget-content">
                <form class="form-horizontal" role="form" method="POST" action="{{ isset($data)? route('peg_jadwal.save'):route('peg_jadwal.save') }}" novalidate="novalidate">
                  {{ csrf_field() }}
                  <input type="hidden" name="id" value="{{ $data['pegawai']->id or 0 }}">
                  <fieldset>
                    <div class="control-group {{ $errors->has('pegawai') ? 'error' : '' }}">
                        <label for="nama" class="control-label">Nama / NIP Pegawai</label>

                        <div class="controls">
                            <?php $selected_pegawai = isset($data)?$data['pegawai']->id:old('pegawai') ?>
                            {{ Form::select('pegawai[]', $pegawai, $selected_pegawai, ['id' => 'pegawai','class'=>'span4', 'multiple' => 'multiple']) }}

                            @if ($errors->has('pegawai'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('pegawai') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="control-group {{ $errors->has('jadwal') ? 'error' : '' }}">
                        <label for="type" class="control-label">Jadwal</label>

                        <div class="controls">
                            <?php $selected_data = isset($data)?$data['peg_jadwal']->id:old('jadwal') ?>
                            {{ Form::select('jadwal[]', $jadwal, $selected_data, ['id' => 'jadwal', 'class' => "span4", 'multiple' => 'multiple']) }}

                            @if ($errors->has('jadwal'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('jadwal') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-actions">
                      <button type="submit" class="btn btn-primary">Simpan</button>
                      <a href="{{ route('peg_jadwal.list') }} " class="btn">Batal</a>
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
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-button.js') }}"></script>
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}" ></script>
<script src="{{ asset('js/bootstrap-datepicker.id.min.js') }}" charset="UTF-8"></script>
<script>

$(function() {
  $('#jadwal').select2({ placeholder: 'Pilih Jadwal',maximumSelectionLength: 3 });
  $('#pegawai').select2({
    placeholder: 'Pilih Pegawai',
    allowClear: true,
    maximumSelectionLength: 3,
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
        markup += "<div class='select2-result-repository__description'>" + repo.id + "</div>";
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

});
</script>
@endpush
