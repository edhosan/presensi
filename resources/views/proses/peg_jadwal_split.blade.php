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
	        {!! Breadcrumbs::render('peg_jadwal.list') !!}
            <div class="row">
	            <div class="span12">
              		<div class="widget">
		                <div class="widget-header">
    		              <i class="icon-list"></i>
            		      <h3>Daftar Jadwal Pegawai</h3>
                		</div>               		
		                <div class="widget-content">
		                	<div class="row">
		                		<div class="span5">
		                			<form class="well form-search" role="form" method="post">
	   				                  {{ csrf_field() }}
	   				                  <label>Pilih jadwal kerja :</label>
		                            <?php $selected_data = isset($data)?$data['peg_jadwal']->id:old('jadwal') ?>
        		                    {{ Form::select('jadwal[]', $jadwal, $selected_data, ['id' => 'jadwal', 'class' => "span3"]) }}
		                			</form>
		                		</div>
		                	</div>
		                	<div class="row">
		               	    	<div class="span5">
		   		                  <table class="table table-striped table-bordered display nowrap" id="pegjadwal-table" width="100%" >
				                      <thead>
				                          <tr>
				                              <th></th>
	   			                              <th></th>
				                              <th>Nip</th>
				                              <th>OPD</th>
				                              <th>Nama</th>
				                          </tr>
				                      </thead>
				                  </table>
			                	</div>
			                	<div class="span1">
			                		<button class="btn btn-small" style="margin-top: 100px"><i class="icon-chevron-left"></i></button><br>
	             			        <button class="btn btn-small" style="margin-top: 25px"><i class="icon-chevron-right"></i></button>
			                	</div>
			                	<div class="span5">
			                		<table class="table table-striped table-bordered display nowrap" id="pegjadwal-table" width="100%" >
				                      <thead>
				                          <tr>
				                              <th></th>
	   			                              <th></th>
				                              <th>Nip</th>
				                              <th>OPD</th>
				                              <th>Nama</th>
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
    var table = $('#pegjadwal-table').DataTable({
    	dom: 'Bfrtip',
        scrollX: true,
        select: {
            style:    'multi',
            selector: 'td:first-child'
        },
        buttons: [
          'selectAll',
          'selectNone',
        ],
        processing: true,
        serverSide: true,
        ajax: '{{ url("api/peg_jadwal_list?api_token=") }}{{ Auth::user()->api_token }}',
        columns: [
            { orderable: false, className: 'select-checkbox', data: null, defaultContent:'', searchable: false },
            { data: 'id', name: 'id', visible: false },
            { data: 'nip', name: 'nip' },
            { data: 'nama_unker', name: 'nama_unker', width:'250px', visible: false },
            { data: 'nama', name: 'nama', width: '150px' },
        ],
        drawCallback: function( settings ){
          var api = this.api();
          var rows = api.rows( {page:'current'} ).nodes();
          var last=null;

          api.column(3, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="5">'+group+'</td></tr>'
                    );

                    last = group;
                }
            } );
        }
    });
});
</script>
@endpush
