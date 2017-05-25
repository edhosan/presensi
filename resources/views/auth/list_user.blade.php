@extends('layouts.app')
@push('css')
<link href="{{ asset('datatables.net-dt/css/jquery.dataTables.css') }}" rel="stylesheet">
<link href="{{ asset('datatables.net-buttons-dt/buttons.dataTables.css') }}" rel="stylesheet">
<link href="{{ asset('datatables.net-select-dt/select.dataTables.css') }}" rel="stylesheet">
@endpush

@section('content')
@if(Auth::check())
   @include('partial.subnavbar')
@endif
<div class="main">
  <div class="main-inner">
    <div class="container">
      {!! Breadcrumbs::render('user') !!}
      <div class="row">
        <div class="span12">
          <div class="widget">
            <div class="widget-header">
              <i class="icon-list"></i>
              <h3>Daftar User</h3>
            </div>

            <div class="widget-content">
              <table class="table table-striped table-bordered" id="users-table" width="100%" >
                  <thead>
                      <tr>
                          <th></th>
                          <th></th>
                          <th>Id</th>
                          <th>Username</th>
                          <th>Name</th>
                          <th>OPD</th>
                          <th>Created At</th>
                          <th>Updated At</th>
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
  <script>
  function format ( d ) {
      var head = '<thead>'+
                  '<tr>'+
                    '<th>Id</th>'+
                    '<th>Tipe User</th>'+
                    '<th>Keterangan</th>'+
                    '<th>Created At</th>'+
                    '<th>Updated At</th>'+
                  '</tr>'+
                '</thead>';

      var content = '';
      $.each(d.roles, function(key, value) {
        content += '<tr>'+
                    '<td>'+value.name+'</td>'+
                    '<td>'+value.display_name+'</td>'+
                    '<td>'+value.description+'</td>'+
                    '<td>'+value.created_at+'</td>'+
                    '<td>'+value.updated_at+'</td>'+
                   '</tr>';
      });

      return '<table class="table table-bordered">'+
              head+
              content+
             '</table>';
  }

  $(function() {
      var table = $('#users-table').DataTable({
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
                    window.location.href = "{{ route('register') }}";
                  }
              },
              {
                  text: '<i class="icon-edit"> Edit</i>',
                  action: function ( e, dt, node, config ) {
                      var data = dt.row( { selected: true } ).data();
                      var newUrl = "{{ url('user_edit') }}";
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
                                  url: '{{ url("api/delete_user?api_token=") }}{{ Auth::user()->api_token }}',
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
          ajax: '{{ url("api/dt_user?api_token=") }}{{ Auth::user()->api_token }}',
          columns: [
              { orderable: false, className: 'select-checkbox', data: null, defaultContent:'', searchable: false },
              { className: 'details-control', orderable: false, data: null, defaultContent: '', searchable: false },
              { data: 'id', name: 'id' },
              { data: 'username', name: 'username' },
              { data: 'name', name: 'name' },
              { data: 'nm_unker', name: 'nm_unker', width: "25%" },
              { data: 'created_at', name: 'created_at' },
              { data: 'updated_at', name: 'updated_at' }
          ]
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

      $('#users-table').on('click', 'td.details-control', function () {
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
  });
  </script>
@endpush
