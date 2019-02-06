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
          {!! Breadcrumbs::render('referensi.tpp.rincian_pengeluaran', $kategori, $pengeluaran) !!}
          <div class="row">
            <div class="span12">
              <div class="widget">
                <div class="widget-header">
                  <i class="icon-list"></i>
                  <h3>Daftar Rincian Pengeluaran [{{ $pengeluaran->jns_pengeluaran }}]</h3>
                </div>

                <div class="widget-content">
                  <table class="table table-striped table-bordered" id="kategori-table" width="100%" >
                      <thead>
                          <tr>                     
<<<<<<< HEAD
                              <th></th>
                              <th></th>                       
                              <th></th>
                              <th>Dasar Pengeluaran</th>
=======
                              <th rowspan="2"></th>
                              <th rowspan="2"></th>                       
                              <th rowspan="2">Nama Pengeluaran</th>
                              <th colspan="3">Lokasi</th>
                              <th rowspan="2">Keterangan</th>
                          </tr>
                          <tr>                                    
                            <th>Biasa</th>
                            <th>Terpencil</th>
                            <th>Sangat Terpencil</th>                            
>>>>>>> 6273a34b43f3376acdcc2edf5767e88eddce0d3b
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
    var table = $('#kategori-table').DataTable({
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
                  window.location.href = "{{ route('tpp.rincian_pengeluaran.create', [$kategori->id, $pengeluaran->id]) }}";
                }
            },
            {
                text: '<i class="icon-edit"> Edit</i>',
                action: function ( e, dt, node, config ) {
                    var data = dt.row( { selected: true } ).data();
                    var newUrl = "{{ url('/tpp/jenis_pengeluaran/edit') }}";
                    window.location.href = newUrl+"/{{ $kategori->id }}/"+data.id;
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
                                url: '{{ url("api/delete_jns_pengeluaran?api_token=") }}{{ Auth::user()->api_token }}',
                                type: 'post',
                                dataType: "json",
                                data: { 
                                  data: arrData,
                                  kategori_id: '{{ $kategori->id }}' 
                                },
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
              text: '<i class="icon-list"> Rincian Pengeluaran</i>',
              action: function ( e, dt, node, config ) {
                  var data = dt.row( { selected: true } ).data();
                  var newUrl = "{{ url('/tpp/kategori/edit') }}";
                  window.location.href = newUrl+"/"+data.id;
              },
              enabled: false
            },

        ],
        processing: true,
        serverSide: true,
        ajax: '{{ url("api/get_jns_pengeluaran/$kategori->id?api_token=") }}{{ Auth::user()->api_token }}',
        columns: [
            { orderable: false, className: 'select-checkbox', data: null, defaultContent:'', searchable: false },
            { data: 'id', name: 'id', visible: false },
            { data: 'jns_pengeluaran', name: 'jns_pengeluaran' },
            { data: 'kriteria', name: 'kriteria' }
        ]       
    });

    table.on( 'select', function (e, dt, type, indexes) {
        var selectedRows = table.rows( { selected: true } ).count();

        table.button( 1 ).enable( selectedRows >= 1 );
        table.button( 2 ).enable( selectedRows >= 1 );
        table.button( 3 ).enable( selectedRows >= 1 );
    } );

    table.on( 'deselect', function ( e, dt, type, indexes ) {
        var selectedRows = table.rows( { selected: true } ).count();

        table.button( 1 ).enable( selectedRows >= 1 );
        table.button( 2 ).enable( selectedRows >= 1 );
        table.button( 3 ).enable( selectedRows >= 1 );
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
