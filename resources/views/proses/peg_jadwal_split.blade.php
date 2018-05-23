@extends('layouts.app')

@push('css')
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
		                		<div class="span11">
		                			<form class="well form-search" role="form" method="post">
	   				                  {{ csrf_field() }}
	   				                  <label>Pilih jadwal kerja :</label>
		                            <?php $selected_data = isset($data)?$data['peg_jadwal']->id:old('jadwal') ?>
        		                    {{ Form::select('jadwal[]', $jadwal, $selected_data, ['id' => 'jadwal_select', 'class' => "span3"]) }}
		                			</form>
		                		</div>
		                	</div>
		                	<div class="row">
		               	    	<div class="span5">
		               	    	 <fieldset>
		               	    	 	<legend>Jadwal Kerja Pegawai</legend>
		               	    	 	<table class="table table-striped table-bordered display" id="pegjadwal-table" width="100%" >
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
		               	    	 </fieldset>		   		                 
			                	</div>
			                	<div class="span1">
			                		<button class="btn btn-small" style="margin-top: 100px" id="btn-jadwal-add"><i class="icon-chevron-left"></i></button><br>
	             			        <button class="btn btn-small" style="margin-top: 25px" id="btn-jadwal-remove"><i class="icon-chevron-right"></i></button>
			                	</div>
			                	<div class="span5">
			                		<fieldset>
			                			<legend>Daftar Pegawai</legend>
			               				<table class="table table-striped table-bordered display" width="100%" id="daftarpeg-table">
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
			                		</fieldset>
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
        language:{
        	processing: '<i><span class="loader"></span></i>'
        },
        serverSide: true,
        ajax: '{{ url("api/peg_jadwal_list?api_token=") }}{{ Auth::user()->api_token }}',       
        columns: [
            { orderable: false, className: 'select-checkbox', data: null, defaultContent:'', searchable: false, width: '15px' },
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

    $('#pegjadwal-table').on('preXhr.dt', function(e, settings, data){
  		NProgress.start();
    });

    $('#pegjadwal-table').on('xhr.dt', function(e, settings, data){
	   	NProgress.done();
    });

    $('#jadwal_select').change(function() {    
    	table.ajax.url('{{ url("api/peg_jadwal_list?api_token=") }}{{ Auth::user()->api_token }}&jadwal_id='+$(this).val()).load();
    });

    var table_daftar_pegawai = $('#daftarpeg-table').DataTable({
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
        language:{
        	processing: '<i><span class="loader"></span></i>'
        },
        serverSide: true,
        ajax: '{{ url("api/datainduk_list?api_token=") }}{{ Auth::user()->api_token }}',
        columns: [
            { orderable: false, className: 'select-checkbox', data: null, defaultContent:'', searchable: false, width: '15px' },
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

    $('#daftarpeg-table').on('preXhr.dt', function(e, settings, data){
      NProgress.start();
    });

    $('#daftarpeg-table').on('xhr.dt', function(e, settings, data){
      NProgress.done();
    });

    $('#btn-jadwal-add').click(function(){
      var selectedRows = table_daftar_pegawai.rows( { selected: true } ).data().pluck('id');

      var arrData = [];
      $.each(selectedRows, function(key, value) {
        arrData[key] = value;
      });
      
      console.log(arrData);
    });

    $('#btn-jadwal-remove').click(function(){
      alert('');
    });

});
</script>
@endpush
