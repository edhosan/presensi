@extends('layouts.app')
@push('css')
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
          {!! Breadcrumbs::render('datainduk_list') !!}
          <div class="row">
            <div class="span12">
              <div class="widget">
                <div class="widget-header">
                  <i class="icon-list"></i>
                  <h3>Daftar Pegawai</h3>
                </div>

                <div class="widget-content">
                  <table class="table table-striped table-bordered display nowrap" id="datainduk-table" width="100%" >
                      <thead>
                          <tr>
                              <th></th>
                              <th>ID</th>
                              <th>Nip</th>
                              <th>Id Finger</th>
                              <th>OPD</th>
                              <th>Nama</th>
                              <th>Pangkat / Gol</th>
                              <th>Jabatan</th>
                              <th>Terminal</th>
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
<script src="{{ asset('jquery/jquery.min.js') }}"></script>
<script src="{{ asset('datatables.net/js/jquery.dataTables.js') }}"></script>
<script src="{{ asset('js/twitter.datatables.js') }}"></script>
<script src="{{ asset('datatables.net-buttons/dataTables.buttons.js') }}"></script>
<script src="{{ asset('datatables.net-buttons/buttons.print.js') }}"></script>
<script src="{{ asset('datatables.net-buttons/buttons.colVis.js') }}"></script>
<script src="{{ asset('datatables.net-select/dataTables.select.js') }}"></script>
<script src="{{ asset('js/dataTables.rowGroup.min.js') }}"></script>
<script>
$(function() {
    var table = $('#datainduk-table').DataTable({
        dom: 'Bfrtip',
        scrollX: true,
        select: {
            style:    'multi',
            selector: 'td:first-child'
        },
        buttons: [
          'selectAll',
          'selectNone',
            {
                text: '<i class="icon-plus"> Tambah Data</i>',
                titleAttr: 'Tambah Data',
                action: function ( e, dt, node, config ) {
                  window.location.href = "{{ route('datainduk_form') }}";
                }
            },
            {
                text: '<i class="icon-edit"> Edit</i>',
                action: function ( e, dt, node, config ) {
                    var data = dt.row( { selected: true } ).data();
                    var newUrl = "{{ url('datainduk_edit') }}";
                    window.location.href = newUrl+"/"+data.id;
                },
                enabled: false
            },
            {
                text: '<i class="icon-remove"> Hapus</i>',
                action: function ( e, dt, node, config ) {
                  toastr.info("Apakah anda yakin ingin menghapus data ini?<br/><button type='button' id='confirmYes' class='btn btn-danger'>Ya</button> <button type='button' id='confirmNo' class='btn'>Tidak</button>",'Konfirmasi?',
                  {
                      positionClass: "toast-top-center",
                      timeOut: 0,
                      tapToDismiss: false,
                      closeButton: false,
                      allowHtml: true,
                      onShown: function (toast) {
                          $("#confirmYes").click(function(){
                            var data = dt.rows( { selected: true } ).data().pluck('id');

                            var arrData = [];
                            $.each(data, function(key, value) {
                              arrData[key] = value;
                            });

                            $.ajax({
                                url: '{{ url("api/datainduk_delete?api_token=") }}{{ Auth::user()->api_token }}',
                                type: 'post',
                                dataType: "json",
                                data: { data: arrData },
                                success: function(response){
                                  table.ajax.reload();
                                },
                                error: function(error){
                                  console.log(error);
                                }
                            });
                            toastr.remove();
                          });

                          $("#confirmNo").click(function(){
                            toastr.remove();
                          });
                        }
                  });
                },
                enabled: false
            },
            {
                extend: 'print',
                customize: function ( win ) {
                  var body = $(win.document.body);
                  body.find('h1').remove();
                  var h3 = '<h3 style="text-align: center">PEMERINTAH KABUPATEN BERAU</h3>';
                  var h4 = '<h4 style="text-align: center">DATA INDUK PEGAWAI</h4>';
                  var br = '<br>';
                  body.prepend(h3, h4, br);
                },
                exportOptions: {
                    columns: ':visible'
                },
                autoPrint: true
            },
            'colvis'
        ],
        processing: true,
        serverSide: true,
        ajax: '{{ url("api/datainduk_list?api_token=") }}{{ Auth::user()->api_token }}',
        columns: [
            { orderable: false, className: 'select-checkbox', data: null, defaultContent:'', searchable: false },
            { data: 'id', name: 'id', visible: false },
            { data: 'nip', name: 'nip' },
            { data: 'id_finger', name: 'id_finger' },
            { data: 'nama_unker', name: 'nama_unker', width:'250px', visible: false },
            { data: 'nama', name: 'nama', width: '150px' },
            { data: 'pangkat', name: 'pangkat', width: '140px' },
            { data: 'nama_jabatan', name: 'nama_jabatan', width: '300px' },
            { data: 'terminal', name: 'terminal', orderable: false }
        ],
        drawCallback: function( settings ){
          var api = this.api();
          var rows = api.rows( {page:'current'} ).nodes();
          var last=null;

          api.column(4, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="9">'+group+'</td></tr>'
                    );

                    last = group;
                }
            } );
        }
    });

    table.on( 'select', function (e, dt, type, indexes) {
        var selectedRows = table.rows( { selected: true } ).count();

        table.button( 1 ).enable( selectedRows >= 1 );
        table.button( 3 ).enable( selectedRows >= 1 );
        table.button( 4 ).enable( selectedRows >= 1 );
    } );

    table.on( 'deselect', function ( e, dt, type, indexes ) {
        var selectedRows = table.rows( { selected: true } ).count();

        table.button( 1 ).enable( selectedRows >= 1 );
        table.button( 3 ).enable( selectedRows >= 1 );
        table.button( 4 ).enable( selectedRows >= 1 );
    } );
});
</script>
@endpush
