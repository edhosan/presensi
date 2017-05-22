@extends('layouts.app')
@push('css')
<link href="{{ asset('datatables.net-dt/css/jquery.dataTables.css') }}" rel="stylesheet">
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
              <table class="table table-striped table-bordered" id="users-table">
                  <thead>
                      <tr>
                          <th>Id</th>
                          <th>Name</th>
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
