@extends('layouts.app')
@push('css')
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<link href="{{ asset('datatables.net-dt/css/jquery.dataTables.css') }}" rel="stylesheet">
<link href="{{ asset('datatables.net-buttons-dt/buttons.dataTables.css') }}" rel="stylesheet">
<link href="{{ asset('datatables.net-select-dt/select.dataTables.css') }}" rel="stylesheet">
<link href="{{ asset('css/rowGroup.dataTables.min.css') }}" rel="stylesheet">

@endpush
@section('content')
@if(Auth::check())
   @include('partial.subnavbar')
@endif
<div class="main">
  <div class="main-inner">
      <div class="container">
          {!! Breadcrumbs::render('riwayat') !!}
          <div class="row">
            <div class="span12">
              <div class="widget">
                <div class="widget-header">
                  <i class="icon-file"></i>
                  <h3>Riwayat Fingerprint</h3>
                </div>

                <div class="widget-content">
                  <form class="form-horizontal" role="form" method="POST" id="form" action="{{ route('riwayat.absensi') }}" novalidate="novalidate">
                    {{ csrf_field() }}
                    <fieldset>                    
                      <div class="control-group {{ $errors->has('tanggal') ? 'error' : '' }}">
                          <label for="tanggal" class="control-label">Tanggal</label>
                          <div class="controls controls-row">                         
                            <input id="tanggal" type="text" class="span2" name="tanggal" value="{{ old('tanggal') }}">                           

                            @if ($errors->has('tanggal'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('tanggal') }}</strong>
                                </span>
                            @endif                          
                          </div>
                      </div>

                      <div class="control-group {{ $errors->has('nama') ? 'error' : '' }}">
                          <label for="nama" class="control-label">Nama / NIP Pegawai</label>

                          <div class="controls">
                            <select name="peg" id="peg" class="span5"></select>

                              @if ($errors->has('nama'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('nama') }}</strong>
                                  </span>
                              @endif
                          </div>
                      </div>          

                      <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Lihat Data</button>
                      </div>
                    </fieldset>

                  </form>

                  <table class="table table-striped table-bordered display nowrap" id="riwayat-table" width="100%" >
                      <thead>
                          <tr>                           
                              <th>TANGGAL</th>
                              <th>WAKTU FINGER</th>
                          </tr>
                      </thead>
                      <tbody>
                        <tr></tr>
                      </tbody>
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

  $('#tanggal').datepicker( formatCalendar );
  
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
          opd: '{{ Auth::user()->unker }}'
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

  var form = $('#form');
  form.on('submit', function() {    
       NProgress.start();
       var errorsHtml= '';

        $.ajax({
          url       : '{{ url("/api/riwayat/get_riwayat_absensi?api_token=") }}{{ Auth::user()->api_token }}',
          type      : form.attr('method'),
          data      : form.serialize(),
          dataType  : 'json',
          async     : true,
          success   : function( json ) {        
            if(json.status == false){
              $.each( json.validator, function( key, value ) {
                errorsHtml += '<li>' + value[0] + '</li>';
              });
              toastr.error( errorsHtml , "Error ");
            }else{
              var html = '';
              $.each(json.data, function(key, value){
                html += '<tr>';
                html += '<td> '+ value.tanggal +' </td>';
                html += '<td> '+ value.jam +' </td>';
                html += '</tr>';
              });
              $('#riwayat-table').find('tbody').empty().append(html);            
            }      
            NProgress.done();
          },
          error     : function( jqXhr, json, errorThrown) {
            NProgress.done();
            console.log(jqXhr);          
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
