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
          {!! Breadcrumbs::render('jadwal_list') !!}
          <div class="row">
            <div class="span12">
              <div class="widget">
                <div class="widget-header">
                  <i class="icon-list"></i>
                  <h3>Daftar Jadwal Kerja</h3>
                </div>

                <div class="widget-content">
                  <table class="table table-striped table-bordered" id="jadwal-table" width="100%" >
                      <thead>
                          <tr>
                              <th></th>
                              <th></th>
                              <th></th>
                              <th>OPD</th>
                              <th>Nama</th>
                              <th>Judul</th>
                              <th>Tanggal Berlaku</th>
                              <th>Tanggal Berakhir</th>
                              <th>Action</th>
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
<script src="{{ asset('datatables.net-select/dataTables.select.js') }}"></script>
<script src="{{ asset('js/dataTables.rowGroup.min.js') }}"></script>
<script>
$(function() {
    function format ( d ) {
      var head = '<thead>'+
                  '<tr>'+
                    '<th>Id</th>'+
                    '<th>Hari</th>'+
                    '<th>Jam Masuk</th>'+
                    '<th>Jam Pulang</th>'+
                    '<th>Toleransi Terlambat</th>'+
                    '<th>Toleransi Pulang</th>'+
                    '<th>Batas Absensi Masuk</th>'+
                    '<th>Batas Absensi Siang 1</th>'+
                    '<th>Batas Absensi Siang 2</th>'+
                    '<th>Batas Absensi Pulang</th>'+
                    '<th>Action</th>'+
                  '</tr>'+
                '</thead>';

        var content = '';
        var urlEdit = '{{ route("hari.edit") }}';
        var urlDelete = '{{ route("hari.delete") }}';

        $.ajax({
          type: 'post',
          url: '{{ url("api/hari?api_token=") }}{{ Auth::user()->api_token }}',
          dataType: 'json',
          data:{
            id: d.id
          },
          async: false,
          success: function(response){
            $.each(response, function(key, value) {
              content += '<tr>'+
                          '<td>'+value.id+'</td>'+
                          '<td>'+value.hari+'</td>'+
                          '<td>'+value.jam_masuk+'</td>'+
                          '<td>'+value.jam_pulang+'</td>'+
                          '<td>'+value.toleransi_terlambat+'</td>'+
                          '<td>'+value.toleransi_pulang+'</td>'+
                          '<td>'+value.absensi_masuk+'</td>'+
                          '<td>'+value.absensi_siang_1+'</td>'+
                          '<td>'+value.absensi_siang_2+'</td>'+
                          '<td>'+value.absensi_pulang+'</td>'+
                          '<td nowrap>'+
                            '<a href='+urlEdit+'?id='+value.id+' class="btn btn-mini btn-success"><i class="icon-edit"> Edit</i></a> '+
                            '<a href='+urlDelete+'?id='+value.id+' class="btn btn-mini btn-danger"><i class="icon-remove"> Hapus</i></a> '+
                          '</td>'+
                         '</tr>';
            });
          }
        });

        return '<table class="table table-bordered">'+
                head+
                content+
               '</table>';
    }

    var table = $('#jadwal-table').DataTable({
        dom: 'Bfrtip',
        scrollX: true,
        select: {
            style:    'multi',
            selector: 'td:first-child'
        },
        buttons: [
            {
                text: '<i class="icon-plus"> Tambah Data</i>',
                titleAttr: 'Tambah Data',
                action: function ( e, dt, node, config ) {
                  window.location.href = "{{ route('jadwal_create') }}";
                }
            },
            {
                text: '<i class="icon-edit"> Edit</i>',
                action: function ( e, dt, node, config ) {
                    var data = dt.row( { selected: true } ).data();
                    var newUrl = "{{ url('jadwal_edit') }}";
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
                                url: '{{ url("api/jadwal_delete?api_token=") }}{{ Auth::user()->api_token }}',
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
        ],
        processing: true,
        serverSide: true,
        ajax: '{{ url("api/jadwal_list?api_token=") }}{{ Auth::user()->api_token }}',
        columns: [
            { orderable: false, className: 'select-checkbox', data: null, defaultContent:'', searchable: false },
            { className: 'details-control', orderable: false, data: null, defaultContent: '', searchable: false },
            { data: 'id', name: 'id', visible: false },
            { data: 'nama_unker', name: 'nama_unker',width:'250px', visible: false },
            { data: 'name', name: 'name' },
            { data: 'title', name: 'title' },
            { data: 'start', name: 'start' },
            { data: 'end', name: 'end' },
            { data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        drawCallback: function( settings ){
          var api = this.api();
          var rows = api.rows( {page:'current'} ).nodes();
          var last=null;

          api.column(3, {page:'current'} ).data().each( function ( group, i ) {
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
        table.button( 2 ).enable( selectedRows >= 1 );
    } );

    table.on( 'deselect', function ( e, dt, type, indexes ) {
        var selectedRows = table.rows( { selected: true } ).count();

        table.button( 1 ).enable( selectedRows >= 1 );
        table.button( 2 ).enable( selectedRows >= 1 );
    } );

    $('#jadwal-table').on('click', 'td.details-control', function () {
       var tr = $(this).closest('tr');
       var row = table.row( tr );

       if ( row.child.isShown() ) {
           // This row is already open - close it
           row.child.hide();
           tr.removeClass('shown');
       }
       else {
           // Open this row
           row.child( format(row.data()) ).show();
           tr.addClass('shown');
       }
   } );

   // Order by the grouping
    $('#jadwal-table').on( 'click', 'tr.group', function () {
        var currentOrder = table.order()[0];
        if ( currentOrder[0] === 2 && currentOrder[1] === 'asc' ) {
            table.order( [ 3, 'desc' ] ).draw();
        }
        else {
            table.order( [ 3, 'asc' ] ).draw();
        }
    } );
});
</script>
@endpush
