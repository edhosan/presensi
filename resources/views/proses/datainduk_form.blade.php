@extends('layouts.app')
@push('css')
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<style media="screen">
.loader {
  border: 5px solid #f3f3f3; /* Light grey */
  border-top: 5px solid #3498db; /* Blue */
  border-radius: 50%;
  width: 16px;
  height: 16px;
  animation: spin 2s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
@endpush
@section('content')
  <div class="main">
    <div class="main-inner">
      <div class="container">
        <div class="row">
          <div class="span12">
            {!! Breadcrumbs::render('datainduk_form') !!}
            <div class="widget">
              <div class="widget-header">
                <i class="icon-file"></i>
                <h3>Form Data Induk Pegawai</h3>
                <div class="pull-right">
                  <select name="peg" id="peg" class="search-query"></select>
                </div>
              </div>

              <div class="widget-content">
                <form class="form-horizontal" role="form" method="POST" action="{{ isset($data)? route('datainduk_update'):route('datainduk_create') }}" novalidate="novalidate">
                  {{ csrf_field() }}
                  <input type="hidden" name="id" value="{{ $data->id or 0 }}">
                  <div class="span5">
                    <fieldset>
                      <div class="control-group {{ $errors->has('type') ? 'error' : '' }}">
                          <label for="type" class="control-label">Status</label>

                          <div class="controls">
                            @php $type = isset($data)?$data->type:old('type') @endphp
                            <label class="radio inline"><input type="radio" name="type" value="pns" @if($type == 'pns') checked="checked" @endif > PNS</label>
                            <label class="radio inline"><input type="radio" name="type" value="nonpns" @if($type == 'nonpns') checked="checked" @endif> NON-PNS</label>

                            @if ($errors->has('type'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('type') }}</strong>
                                </span>
                            @endif
                          </div>
                      </div>

                      <div class="control-group {{ $errors->has('opd') ? 'error' : '' }}">
                          <label for="opd" class="control-label">OPD</label>

                          <div class="controls">
                              <?php $selected_opd = isset($data)?$data->id_unker:old('opd') ?>
                              {{ Form::select('opd', $opd, $selected_opd, ['id' => 'opd', 'placeholder' => "Please Select", 'class' => 'span4']) }}

                              @if ($errors->has('opd'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('opd') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="control-group {{ $errors->has('sub_unit') ? 'error' : '' }}">
                          <label for="sub_unit" class="control-label">Sub Unit</label>

                          <div class="controls">
                              @php $selected_subunit = isset($data)?$data->id_subunit:old('sub_unit') @endphp
                              <select class="span4" name="sub_unit" id="subunit">
                                <option value="">Pilih Sub Unit</option>
                                @foreach($subunit as $unit)
                                  <optgroup label="{{ $unit->nama_unker}}">
                                    @foreach($unit->subUnit as $item)
                                    <option value="{{ $item->id_subunit }}" @if($selected_subunit == $item->id_subunit) selected="selected" @endif>
                                      {{ $item->nama_subunit }}
                                    </option>
                                    @endforeach
                                  </optgroup>
                                @endforeach
                              </select>
                              <div class="loader" id="loaderSubUnit"></div>
                              @if ($errors->has('sub_unit'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('sub_unit') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div id="d_nip" class="control-group {{ $errors->has('nip') ? 'error' : '' }}">
                          <label for="nip" class="control-label">NIP</label>

                          <div class="controls">
                              <input id="nip" type="text" class="span4" name="nip" value="{{ $data->nip or old('nip') }}" @if($type == 'nonpns') readonly="readonly" @endif autofocus>

                              @if ($errors->has('nip'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('nip') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="control-group {{ $errors->has('nama') ? 'error' : '' }}">
                          <label for="nama" class="control-label">Nama</label>

                          <div class="controls">
                              <input id="nama" type="text" class="span4" name="nama" value="{{ $data->nama or old('nama') }}" autofocus>

                              @if ($errors->has('nama'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('nama') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="control-group {{ $errors->has('gelar_depan') ? 'error' : '' }}">
                          <label for="gelar_depan" class="control-label">Gelar Depan</label>

                          <div class="controls">
                              <input id="gelar_depan" type="text" class="span2" name="gelar_depan" value="{{ $data->gelar_depan or old('gelar_depan') }}">

                              @if ($errors->has('gelar_depan'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('gelar_depan') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="control-group {{ $errors->has('gelar_belakang') ? 'error' : '' }}">
                          <label for="gelar_belakang" class="control-label">Gelar Belakang</label>

                          <div class="controls">
                              <input id="gelar_belakang" type="text" class="span2" name="gelar_belakang" value="{{ $data->gelar_belakang or old('gelar_belakang') }}">

                              @if ($errors->has('gelar_belakang'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('gelar_belakang') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="control-group {{ $errors->has('id_finger') ? 'error' : '' }}">
                          <label for="id_finger" class="control-label">Id Fingerprint</label>

                          <div class="controls">
                              <input id="id_finger" type="text" class="span2" name="id_finger" value="{{ $data->id_finger or old('id_finger') }}">
                              <button type="button" id="btnGenerateId" class="btn btn-warning" text="Loading..."> Buat ID</button>
                              @if ($errors->has('id_finger'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('id_finger') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                    </fieldset>
                  </div>

                  <div class="span6">
                    <fieldset>
                      <div class="control-group {{ $errors->has('pangkat') ? 'error' : '' }}">
                          <label for="pangkat" class="control-label">Pangkat / Golongan</label>

                          <div class="controls">     
                              <?php $selected_pangkat = isset($data)?$data->id_pangkat:old('pangkat') ?>
                              {{ Form::select('pangkat', $pangkat, $selected_pangkat, ['id' => 'pangkat', 'placeholder' => "Please Select", 'class' => 'span4' ]) }}

                              @if ($errors->has('pangkat'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('pangkat') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="control-group {{ $errors->has('eselon') ? 'error' : '' }}">
                          <label for="eselon" class="control-label">Eselon</label>

                          <div class="controls">
                              <?php $selected_eselon = isset($data)?$data->id_eselon:old('eselon') ?>
                              {{ Form::select('eselon',$eselon,$selected_eselon, ['id' => 'eselon', 'placeholder' => "Please Select", 'class' => 'span4']) }}

                              @if ($errors->has('eselon'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('eselon') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="control-group {{ $errors->has('jabatan') ? 'error' : '' }}">
                          <label for="jabatan" class="control-label">Jabatan</label>

                          <div class="controls">
                              @php $selected_jabatan = isset($data)?$data->id_jabatan:old('jabatan') @endphp
                              {{ Form::select('jabatan', $jabatan, $selected_jabatan, ['id' => 'jabatan', 'placeholder' => "Please Select", 'class' => 'span4']) }}

                              @if($errors->has('jabatan'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('jabatan') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div id="d_tmt_pangkat" class="control-group {{ $errors->has('tmt_pangkat') ? 'error' : '' }}">
                          <label for="tmt_pangkat" class="control-label">TMT Pangkat</label>

                          <div class="controls">
                              @php
                                $new_date = old('tmt_pangkat');
                                if(!empty($data)){
                                  $new_date = Carbon\Carbon::parse($data->tmt_pangkat)->format('d-m-Y');
                                }
                              @endphp
                              <input id="tmt_pangkat" type="text" name="tmt_pangkat" value="{{ $new_date }}" >
                              @if ($errors->has('tmt_pangkat'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('tmt_pangkat') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                    </fieldset>
                  </div>

                  <div class="span11">
                    <div class="form-actions">
                      <button type="submit" class="btn btn-primary">Simpan</button>
                      <a href="{{ route('datainduk_list') }} " class="btn">Batal</a>
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
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-button.js') }}"></script>
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}" ></script>
<script src="{{ asset('js/bootstrap-datepicker.id.min.js') }}" charset="UTF-8"></script>
<script>

$(function() {
  var formatCalendar = {
    format: 'dd-mm-yyyy',
    language: 'id',
    autoclose : true,
    todayHighlight : true
  };

  @if($type == 'nonpns')
      $('#nip').prop('disabled', true);
      $('#pangkat').prop('disabled', true);
      $('#tmt_pangkat').prop('disabled', true);
  @endif

  $('#tmt_pangkat').datepicker( formatCalendar );
  $('input[type=radio][name=type]').change(function() {
      if (this.value == 'pns') {
        $('#nip').prop('disabled', false);
        $('#pangkat').prop('disabled', false);
        $('#tmt_pangkat').prop('disabled', false);
      }
      else if (this.value == 'nonpns') {
        $('#nip').prop('disabled', true);
        $('#pangkat').prop('disabled', true);
        $('#tmt_pangkat').prop('disabled', true);
      }
  });

  $('#peg').select2({
    placeholder: 'Cari Nama / NIP Pegawai',
    allowClear: true,
    ajax: {
      url: "{{ url('api/pegawai?api_token=') }}{{ Auth::user()->api_token }}",
      dataType: 'json',
      delay: 250,
      data: function(params){
        return {
          q: params.term,
          page: params.page,
          per_page: 10,
          opd: $('#opd').val()
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

  $('#jabatan').select2({
    placeholder: 'Pilih Jabatan',
    allowClear: true,
    ajax: {
      url: "{{ url('api/jabatan?api_token=') }}{{ Auth::user()->api_token }}",
      dataType: 'json',
      delay: 250,
      data: function(params){
        return {
          q: params.term,
          page: params.page,
          per_page: 10,
          eselon: $('#eselon').val()
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
    templateResult: formatRepoJabatan,
    templateSelection: formatRepoSelectionJabatan
  })

  function formatRepoJabatan (repo) {
      if (repo.loading) return repo.text;

      var markup = "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__meta'>" +
          "<div class='select2-result-repository__title'>" + repo.nama_jabatan + "</div>";

      if (repo.description) {
        markup += "<div class='select2-result-repository__description'>" + repo.nama + "</div>";
      }

      markup += "<div class='select2-result-repository__statistics'>" +
        "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i>Eselon: " + repo.nama + "</div>" +
      "</div>" +
      "</div></div>";

      return markup;
  }

  function formatRepoSelectionJabatan (repo) {
    return repo.nama_jabatan || repo.text;
  }

  $('#opd').select2({placeholder: 'Pilih OPD',allowClear: true});
  $('#subunit').select2({placeholder: 'Pilih Sub Unit',allowClear: true});
  $('#pangkat').select2({placeholder: 'Pilih Pangkat / Golongan',allowClear: true});
  $('#eselon').select2({placeholder: 'Pilih Eselon',allowClear: true, minimumResultsForSearch: Infinity});

  $('#peg').on('select2:select', function(e) {
    $('#nip').val(e.params.data.nip).trigger("change");;
    $('#nama').val(e.params.data.nama).trigger("change");;
    $('#opd').val(e.params.data.id_unker).trigger("change");
    $('#subunit').val(e.params.data.id_subunit).trigger("change");
    $("#gelar_depan").val(e.params.data.gelar_depan).trigger("change");
    $("#gelar_belakang").val(e.params.data.gelar_belakang).trigger("change");
    $("#pangkat").val(e.params.data.id_pangkat).trigger("change");
    $("#eselon").val(e.params.data.id_eselon).trigger("change");
    $('#jabatan').append(
       '<option value="' + e.params.data.id_jabatan + '" selected="selected">' + e.params.data.nama_jabatan + '</option>'
     ).trigger('change');
    $("#tmt_pangkat").datepicker('update',e.params.data.tmt_pangkat);
  });

  $('#loaderSubUnit').hide();

  $('#opd').on('select2:select', function(e) {
    $('#loaderSubUnit').show();
    $.ajax({
      url: "{{ url('api/subunit?api_token=') }}{{ Auth::user()->api_token }}",
      type: "POST",
      dataType: "json",
      data: { opd: e.params.data.id },
      success: function(response){
        $('#loaderSubUnit').hide();
        var subunit = $('#subunit');
        subunit.empty();
        subunit.append('<option value="">Please Select</option>');
        $.each(response, function(index, value){
          subunit.append('<optgroup label="'+value.nama_unker+'">');
          $.each(value.sub_unit, function(i, el) {
            subunit.append('<option value="'+el.id_subunit+'">'+el.nama_subunit+'</option>');
          });
          subunit.append('</optgroup>');
        });
      },
      error: function(error){
        console.log(error);
      }
    });
  });

  $('#btnGenerateId').click(function() {
    var btn = $(this);
    btn.button('loading');
    $.ajax({
      url: '{{ url("api/get_idfinger?api_token=") }}{{ Auth::user()->api_token }}',
      type: 'get',
      dataType: "json",
      success: function(response){
        btn.button('reset')
        $('#id_finger').val(response.id_finger)
      },
      error: function(error){
        console.log(error);
      }
    });
  });

});
</script>
@endpush
