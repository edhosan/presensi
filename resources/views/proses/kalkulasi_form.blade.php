@extends('layouts.app')
@push('css')
<link href="{{ asset('easy-autocomplete/dist/easy-autocomplete.min.css') }}" rel="stylesheet">
<link href="{{ asset('easy-autocomplete/dist/easy-autocomplete.themes.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@endpush
@section('content')
@if(Auth::check())
   @include('partial.subnavbar')
@endif
<div class="main">
  <div class="main-inner">
      <div class="container">
          {!! Breadcrumbs::render('kalkulasi.form') !!}
          <div class="row">
            <div class="span12">
              <div class="widget">
                <div class="widget-header">
                  <i class="icon-file"></i>
                  <h3>Form Kalkulasi Kehadiran Pegawai</h3>
                </div>

                <div class="widget-content">
                  <form class="form-horizontal" role="form" method="POST" id="form" action="{{ route('kalkulasi.proses') }}" novalidate="novalidate">
                    {{ csrf_field() }}
                    <fieldset>
                      <div class="control-group {{ $errors->has('opd') ? 'error' : '' }}">
                          <label for="type" class="control-label">OPD</label>

                          <div class="controls">
                              <?php $selected_data = isset($data)?$data->opd:old('opd') ?>
                              {{ Form::select('opd', $opd, $selected_data, ['id' => 'opd', 'placeholder' => "Please Select", 'class' => 'span5']) }}

                              @if ($errors->has('opd'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('opd') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="control-group {{ $errors->has('start') ? 'error' : '' }} {{ $errors->has('end') ? 'error' : '' }}">
                          <label for="start" class="control-label">Tanggal</label>
                          <div class="controls controls-row">
                            @php
                              $start = old('start');
                              if(!empty($data))
                                $start = Carbon\Carbon::parse($data['start'])->format('d-m-Y');
                            @endphp
                            <input id="start" type="text" class="span2" name="start" value="{{ $start }}">
                            &nbsp;s/d&nbsp;
                            @php
                              $end = old('end');
                              if(!empty($data))
                                $end = Carbon\Carbon::parse($data['end'])->format('d-m-Y');
                            @endphp
                            <input id="end" type="text" class="span2" name="end" value="{{ $end }}">

                            @if ($errors->has('start'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('start') }}</strong>
                                </span>
                            @endif

                            @if ($errors->has('end'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('end') }}</strong>
                                </span>
                            @endif
                          </div>
                      </div>

                      <div class="control-group {{ $errors->has('nama') ? 'error' : '' }}">
                          <label for="nama" class="control-label">Nama / NIP Pegawai</label>

                          <div class="controls">
                              <input id="nama" type="text" class="span5 autocomplete" name="nama" value="{{ $data->pegawai->nama or old('nama') }}" autofocus>
                              <input type="hidden" name="id_peg" id="id_peg" value="{{ $data->peg_id or old('id_peg') }}">

                              @if ($errors->has('nama'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('nama') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>

                      <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Proses</button>
                      </div>

                      <div class="progress progress-striped active" id="progressouter">
                       <div class="bar" id="progress"></div>
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
<script>
$(function() {

  var formatCalendar = {
    format: 'dd-mm-yyyy',
    language: 'id',
    autoclose : true,
    todayHighlight : true
  };

  $('#start').datepicker( formatCalendar );
  $('#end').datepicker( formatCalendar );

  var optPeg = {
      url: function(phrase) {
        return "{{ url('api/getNamePeg?api_token=') }}{{ Auth::user()->api_token }}";
      },

      getValue: function(element) {
        return element.nama;
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
          description: "nip"
        }
      },

      list: {
        onSelectItemEvent: function() {
          var value = $("#nama").getSelectedItemData();
          $("#id_peg").val(value.id).trigger("change");
        }
      },

      preparePostData: function(data) {
        data.phrase = $("#nama").val();
        return data;
      }
    };

    $("#nama").easyAutocomplete(optPeg);

  var form = $('#form');

  form.on('submit', function() {
    var progresspump = setInterval(function(){
        /* query the completion percentage from the server */
        $.get("{{ url('kalkulasi_progress') }}", function(data){
          console.log(data);
          /* update the progress bar width */
          $("#progress").css('width',data+'%');
          /* and display the numeric value */
          $("#progress").html(data+'%');
        })
      }, 500);

      $.ajax({
        url       : form.attr('action'),
        type      : form.attr('method'),
        data      : form.serialize(),
        dataType  : 'json',
        success   : function( json ) {
          console.log(json);
          clearInterval(progresspump);
          $("#progress").css('width',json+'%');
          $("#progressouter").removeClass("active");
          $("#progress").html("Done");
        },
        error     : function( jqXhr, json, errorThrown) {
          var errors = jqXhr.responseJSON;
          var errorsHtml= '';
          $.each( errors, function( key, value ) {
             errorsHtml += '<li>' + value[0] + '</li>';
          });
          clearInterval(progresspump);
          toastr.error( errorsHtml , "Error " + jqXhr.status +': '+ errorThrown);
        }
      });

      return false;
  });



});


</script>
@endpush
