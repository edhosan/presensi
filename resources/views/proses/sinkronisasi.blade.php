@extends('layouts.app')
@push('css')
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<link href="{{ asset('datatables.net-dt/css/jquery.dataTables.css') }}" rel="stylesheet">
<link href="{{ asset('datatables.net-buttons-dt/buttons.dataTables.css') }}" rel="stylesheet">
<link href="{{ asset('datatables.net-select-dt/select.dataTables.css') }}" rel="stylesheet">
<link href="{{ asset('css/rowGroup.dataTables.min.css') }}" rel="stylesheet">
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
@if(Auth::check())
   @include('partial.subnavbar')
@endif
<div class="main">
  <div class="main-inner">
      <div class="container">
          {!! Breadcrumbs::render('sinkronisasi') !!}
          <div class="row">
            <div class="span12">
              <div class="widget">
                <div class="widget-header">
                  <i class="icon-file"></i>
                  <h3>Sinkronisasi Data</h3>
                </div>

                <div class="widget-content">
                  <form class="form-horizontal" role="form" method="POST" id="form" action="{{ route('sinkronisasi.proses') }}" novalidate="novalidate">
                    {{ csrf_field() }}
                    <fieldset>
                      @role(['super-admin'])
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
                      @endrole

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
                            <select name="peg[]" id="peg" class="span5" multiple="multiple"></select>

                              @if ($errors->has('nama'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('nama') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>
    
                       @role(['super-admin'])
                      <div class="control-group {{ $errors->has('sinkronisasi') ? 'error' : '' }}">
                          <label for="nama" class="control-label">Sinkronisasi Data</label>

                          <div class="controls">
                              <label class="radio inline">
                                <input type="radio" name="type_sinkronisasi" value="1" @if(old("type_sinkronisasi")=="1") checked @endif>
                                Kalendar Kerja
                              </label>

                              <label class="radio inline">
                                <input type="radio" name="type_sinkronisasi" value="2" @if(old("type_sinkronisasi")=="2") checked @endif>
                                Jumlah Hari Izin
                              </label>

                              <label class="radio inline">
                                <input type="radio" name="type_sinkronisasi" value="3" @if(old("type_sinkronisasi")=="3") checked @endif>
                                Berhalangan Hadir
                              </label>

                              <label class="radio inline">
                                <input type="radio" name="type_sinkronisasi" value="4" @if(old("type_sinkronisasi")=="4") checked @endif>
                                Dispensasi
                              </label>

                              <label class="radio inline">
                                <input type="radio" name="type_sinkronisasi" value="5" @if(old("type_sinkronisasi")=="5") checked @endif>
                                Kehadiran
                              </label>

                              <label class="radio inline">
                                <input type="radio" name="type_sinkronisasi" value="6" @if(old("type_sinkronisasi")=="6") checked @endif>
                                Sinkronisasi
                              </label>

                              @if ($errors->has('sinkronisasi'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('sinkronisasi') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>
                      @endrole

                      <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Sinkronisasi</button>
                        <div class="loader" id="loader"></div>
                      </div>
                    </fieldset>

                  </form>

                  <table class="table table-striped table-bordered display nowrap" id="pegjadwal-table" width="100%" >
                      <thead>
                          <tr>                           
                              <th>TANGGAL</th>
                              <th>NAMA</th>
                              <th>JADWAL KERJA</th>
                              <th>IN</th>
                              <th>OUT</th>
                              <th>JAM KERJA</th>
                              <th>SIANG 1</th>
                              <th>SIANG 2</th>
                              <th>TERLAMBAT</th>
                              <th>PULANG AWAL</th>
                              <th>STATUS</th>
                          </tr>
                      </thead>
                  </table>
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
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}" ></script>
<script src="{{ asset('js/bootstrap-datepicker.id.min.js') }}" charset="UTF-8"></script>
<script src="{{ asset('datatables.net/js/jquery.dataTables.js') }}"></script>
<script src="{{ asset('js/twitter.datatables.js') }}"></script>
<script src="{{ asset('datatables.net-buttons/dataTables.buttons.js') }}"></script>
<script src="{{ asset('datatables.net-select/dataTables.select.js') }}"></script>
<script src="{{ asset('js/dataTables.rowGroup.min.js') }}"></script>
<script>
$(function() {
  $('#loader').hide();
  var formatCalendar = {
    format: 'dd-mm-yyyy',
    language: 'id',
    autoclose : true,
    todayHighlight : true
  };

  $('#start').datepicker( formatCalendar );
  $('#end').datepicker( formatCalendar );
  $('#opd').select2({ placeholder: 'Pilih OPD' });
  $('#peg').select2({
    placeholder: 'Pilih Pegawai',
    allowClear: true,
    ajax: {
      url: "{{ url('api/search_peg?api_token=') }}{{ Auth::user()->api_token }}",
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
      if (repo.loading) return repo.nama;

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
    return repo.nama || repo.nip;
  }

  var table = $('#pegjadwal-table').DataTable({
        dom: 'lfrtip',
        scrollX: true,
        processing: true,
        paging: true,
        searching: true,
        pageLength: 10,
        language:{
          processing: '<i><span class="loader"></span></i>'
        },
        serverSide: true,
        ajax: {
          url: '{{ url("/api/sinkronisasi/hasil?api_token=") }}{{ Auth::user()->api_token }}',
          type: 'GET',
          data: function(d) {
            return $('#form').serialize();
          }
        },
        columns: [
            { data: 'tanggal', name: 'tanggal', width: '50px' },
            { data: 'nama', name: 'nama', width: '150px',visible: false },
            { data: 'name', name: 'name', width: '100px' },
            { data: 'in', name: 'in', width: '20px' },
            { data: 'out', name: 'out', width: '20px' },
            { data: 'jam_kerja', name: 'jam_kerja', width: '20px' },
            { data: 'scan_1', name: 'scan_1', width: '20px' },
            { data: 'scan_2', name: 'scan_2', width: '20px' },
            { data: 'terlambat', name: 'terlambat', width: '20px' },
            { data: 'pulang_awal', name: 'pulang_awal', width: '20px' },
            { data: 'status', name: 'status', width: '20px' }
        ],
        drawCallback: function( settings ){
          var api = this.api();
          var rows = api.rows( {page:'current'} ).nodes();
          var last=null;

          api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="10">'+group+'</td></tr>'
                    );

                    last = group;
                }
            } );
        }

    });

  var form = $('#form');
  form.on('submit', function() {
      $('#loader').show();
       NProgress.start();
       var errorsHtml= '';

        $.ajax({
          url       : form.attr('action'),
          type      : form.attr('method'),
          data      : form.serialize(),
          dataType  : 'json',
          async     : true,
          success   : function( json ) {
            $('#loader').hide();
            if(json.status == false){
              $.each( json.validator, function( key, value ) {
                errorsHtml += '<li>' + value[0] + '</li>';
              });
              toastr.error( errorsHtml , "Error ");
            }else{
              toastr.success( "Sinkronisasi Data Berhasil" , "Success ");
              table.ajax.reload();
            }
            console.log(json.hasil);
            NProgress.done();
          },
          error     : function( jqXhr, json, errorThrown) {
            NProgress.done();
            console.log(errorThrown);
            $('#loader').hide();
            var errors = $.parseJSON(jqXhr.responseText);
            var errorsHtml= '';
            $.each( errors, function( key, value ) {
               errorsHtml += '<li>' + value[0] + '</li>';
            });
            table.ajax.reload();
            toastr.error( errorsHtml , "Error " + jqXhr.status +': ');            
          }
        });
        return false;
    });





});


</script>
@endpush
