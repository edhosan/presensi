@extends('layouts.app')
@push('css')
<link href="{{ asset('datatables.net-dt/css/jquery.dataTables.css') }}" rel="stylesheet">
@endpush

@section('content')
<table class="table table-bordered" id="users-table">
    <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>          
            <th>Created At</th>
            <th>Updated At</th>
        </tr>
    </thead>
</table>
@endsection

@push('script')
  <script src="{{ asset('jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('datatables.net/js/jquery.dataTables.js') }}"></script>

  <script>
  $(function() {
      $('#users-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: '{{ url("api/dt_user?api_token=") }}{{ Auth::user()->api_token }}',
          columns: [
              { data: 'id', name: 'id' },
              { data: 'name', name: 'name' },
              { data: 'created_at', name: 'created_at' },
              { data: 'updated_at', name: 'updated_at' }
          ]
      });
  });
  </script>
@endpush
