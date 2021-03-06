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
          {!! Breadcrumbs::render('authlog.list') !!}
          <div class="row">
            <div class="span12">
              <div class="widget">
                <div class="widget-header">
                  <i class="icon-list"></i>
                  <h3>Auth Log</h3>
                </div>

                <div class="widget-content">
                  <table class="table table-striped table-bordered display nowrap" id="authlog-table" width="100%" >
                      <thead>
                          <tr>
                              <th></th>
                              <th></th>
                              <th>Id Fingerprint</th>
                              <th>Transaction Time</th>
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
<script src="{{ asset('datatables.net-select/dataTables.select.js') }}"></script>
<script>
$(function() {

    var table = $('#authlog-table').DataTable({
        dom: 'Bfrtip',
        scrollX: true,
        select: {
            style:    'multi',
            selector: 'td:first-child'
        },
        buttons: [
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
                            var data = dt.rows( { selected: true } ).data().pluck('IndexKey');

                            var arrData = [];
                            $.each(data, function(key, value) {
                              arrData[key] = value;
                            });

                            $.ajax({
                                url: '{{ url("api/authlog_delete?api_token=") }}{{ Auth::user()->api_token }}',
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
        ajax: '{{ url("api/authlog_list?api_token=") }}{{ Auth::user()->api_token }}',
        columns: [
            { orderable: false, className: 'select-checkbox', data: null, defaultContent:'', searchable: false },
            { data: 'IndexKey', name: 'IndexKey', visible: false },
            { data: 'UserID', name: 'UserID' },
            { data: 'TransactionTime', name: 'TransactionTime' },
            { data: 'terminal.Name', name: 'terminal', orderable: false, searchable: false },
        ]
    });

    table.on( 'select', function (e, dt, type, indexes) {
        var selectedRows = table.rows( { selected: true } ).count();

        table.button( 0 ).enable( selectedRows >= 1 );
    } );

    table.on( 'deselect', function ( e, dt, type, indexes ) {
        var selectedRows = table.rows( { selected: true } ).count();

        table.button( 0 ).enable( selectedRows >= 1 );
    } );
});
</script>
@endpush
